<?php

namespace App\Service\vxWeb;

use vxPHP\Application\Application;
use vxPHP\Constraint\Validator\Ip;
use vxPHP\Service\ServiceInterface;

/**
 * a bruteforce throttling service, heavily inspired by
 * Nextcloud's approach
 *
 * @see <https://github.com/nextcloud/server/blob/master/lib/private/Security/Bruteforce/Throttler.php>
 * @see <https://github.com/nextcloud/server/blob/master/lib/private/User/Session.php>
 *
 * @author Gregor Kofler
 * @version 0.2.0 2021-10-17
 */

class BruteforceThrottler implements ServiceInterface
{
    /**
     * the maximum throttling delay in seconds
     */
    public const MAX_THROTTLE_DELAY = 30;

    /**
     * white listed IPs won't be throttled
     *
     * @var array
     */
    protected array $whiteListedIps;

    /**
     *
     *
     * @param \DateInterval
     */
    protected \DateInterval $cutoff;

    /**
     * @param array $parameters
     * @throws \Exception
     */
    public function setParameters(array $parameters): void
    {
        $this->whiteListedIps = [];

        // parse IP whitelist

        if(isset($parameters['whitelisted_ips'])) {

            $ipv4validator = new Ip('v4');
            $ipV6validator = new Ip('v6');

            foreach(explode(',', $parameters['whitelisted_ips']) as $ipMask) {

                $parts = explode('/', trim($ipMask));

                $ip = $parts[0];
                $maskBits = (int) ($parts[1] ?? 0);

                if($ipv4validator->validate($ip)) {
                    $type = 4;

                    if($maskBits > 32) {
                        throw new \InvalidArgumentException(sprintf("Invalid subnet mask for IPv4. Parsing '%s'.", $ipMask));
                    }
                }
                elseif($ipV6validator->validate($ip)) {
                    $type = 6;

                    if($maskBits > 128) {
                        throw new \InvalidArgumentException(sprintf("Invalid subnet mask for IPv6. Parsing '%s'.", $ipMask));
                    }
                }
                else {
                    throw new \InvalidArgumentException(sprintf("Invalid ip '%s'", $ip));
                }

                $this->whiteListedIps[] = ['type' => $type, 'ip' => $ip, 'mask' => $maskBits];

            }
        }

        // parse cutoff (supplied as seconds)

        if(isset($parameters['cutoff'])) {
            $this->cutoff = new \DateInterval(sprintf("PT%dS", $parameters['cutoff']));
        }

        else {

            // cutoff defaults to 6 hour

            $this->cutoff = new \DateInterval('PT6H');

        }
    }

    /**
     * throttle by sleeping depending on detected action count
     * maximum delay is limited to 30 seconds
     *
     * @param string $ip
     * @param string $action
     * @return BruteforceThrottler
     * @throws \vxPHP\Application\Exception\ApplicationException
     * @throws \vxPHP\Application\Exception\ConfigException
     */
    public function throttle(string $ip, string $action = 'admin_login'): self
    {
        if(!$this->isIpWhiteListed($ip)) {

            $attempts = $this->getAttempts($ip, $action);

            if ($attempts) {
                usleep(min(0.1 * 2 ** $attempts, self::MAX_THROTTLE_DELAY * 1000) * 1000);
            }
        }

        return $this;
    }

    /**
     * @param $ip
     * @return bool
     */
    private function isIpWhiteListed($ip): bool
    {
        if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
            $type = 4;
        }
        else if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
            $type = 6;
        }
        else {
            throw new \InvalidArgumentException(sprintf("IP '%s' is neither IPv4 nor IPv6.", $ip));
        }

        $ipBinary = inet_pton($ip);

        foreach($this->whiteListedIps as $entry) {

            // match only IPv4 and IPv6 addresses with respective whitelist entries

            if($type !== $entry['type']) {
                continue;
            }

            $wlBinary = inet_pton($entry['ip']);

            // no mask? compare raw IPs

            if(!$entry['mask'] && $ipBinary === $wlBinary) {
                return true;
            }

            // compare masked IPs

            for($i = 0; $i < $entry['mask']; ++$i) {

                $part = ord($wlBinary[(int) ($i / 8)]) & (15 << (1 - ($i % 2)));
                $orig = ord($ipBinary[(int) ($i / 8)]) & (15 << (1 - ($i % 2)));

                if ($part !== $orig) {
                    return false;
                }
            }

            return true;

        }

        return false;
    }

    /**
     * @param string $ip
     * @param mixed $data
     * @return BruteforceThrottler
     * @throws \vxPHP\Application\Exception\ApplicationException
     * @throws \vxPHP\Application\Exception\ConfigException
     */
    public function registerAttempt(string $ip, $data): self
    {
        Application::getInstance()->getVxPDO()->insertRecord('bruteforce_attempts', [
            'ip' => $ip,
            'action' => 'admin_login',
            'when' => time(),
            'data' => $data ? json_encode($data): null,
        ]);

        return $this;
    }

    /**
     * clear all specified actions that came from a given ip address
     *
     * @param string $ip
     * @param string $action
     * @return BruteforceThrottler
     * @throws \vxPHP\Application\Exception\ApplicationException
     * @throws \vxPHP\Application\Exception\ConfigException
     */
    public function clearAttempts(string $ip, string $action): self
    {
        Application::getInstance()->getVxPDO()->deleteRecord('bruteforce_attempts', [
            'ip' => $ip,
            'action' => $action
        ]);

        return $this;
    }

    /**
     * get count of attempts of a specified action by a given ip address
     *
     * @param string $ip
     * @param string $action
     * @return int
     * @throws \vxPHP\Application\Exception\ApplicationException
     * @throws \vxPHP\Application\Exception\ConfigException
     */
    public function getAttempts(string $ip, string $action = 'admin_login'): int
    {
        $db = Application::getInstance()->getVxPDO();
        $params = [$ip, $action];

        // only observe attempts dating back a specified time when cutoff is set

        if($this->cutoff) {
            $where =  'AND ' . $db->quoteIdentifier('when') . ' > ?';
            $now = new \DateTime();
            $cutoffDT = clone $now;
            $params[] = $cutoffDT->sub($this->cutoff)->getTimestamp();
        }

        // return record count satisfying conditions

        return $db->doPreparedQuery(sprintf("
          SELECT
            COUNT(*) AS cnt
          FROM
            bruteforce_attempts
          WHERE
            ip = ?
            AND action = ?
            %s
        ", $where ?? ''), $params)->current()['cnt'];
    }
}