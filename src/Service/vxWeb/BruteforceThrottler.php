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
 * @version 0.1.0 2018-04-14
 */

class BruteforceThrottler implements ServiceInterface
{

    /**
     * white listed IPs won't be throttled
     *
     * @var array
     */
    protected $whiteListedIps;

    /**
     *
     *
     * @param \DateInterval
     */
    protected $cutoff;

    /**
     * @param array $parameters
     * @throws \Exception
     */
    public function setParameters(array $parameters) {

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
     * @param $ip
     * @param string $action
     * @return BruteforceThrottler
     */
    public function throttle($ip, $action = 'admin_login'): BruteforceThrottler {

        if(!$this->isIpWhiteListed($ip)) {


            $attempts = $this->getAttempts($ip, $action);

            if ($attempts) {

                $delay = 0.1 * pow(2, $attempts);

                usleep(min($delay, 30000) * 1000);
            }

        }

        return $this;

    }

    /**
     * @param $ip
     * @return bool
     */
    private function isIpWhiteListed($ip): bool {

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

            if(!$entry['mask']) {

                if($ipBinary === $wlBinary) {
                    return true;
                }

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
     */
    public function registerAttempt($ip, $data): BruteforceThrottler {

        Application::getInstance()->getVxPDO()->insertRecord('bruteforce_attempts', [
            'ip' => $ip,
            'action' => 'admin_login',
            'when' => time(),
            'data' => $data ? json_encode($data): null,
        ]);

        return $this;
    }

    /**
     * @param string $ip
     * @param string $action
     * @return BruteforceThrottler
     */
    public function clearAttempts($ip, $action): BruteforceThrottler {

        Application::getInstance()->getVxPDO()->deleteRecord('bruteforce_attempts', [
            'ip' => $ip,
            'action' => $action
        ]);

        return $this;
    }

    /**
     * @param string $ip
     * @param string $action
     * @return int
     */
    public function getAttempts($ip, $action = 'admin_login'): int {

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