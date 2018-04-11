<?php

namespace App\Service\vxWeb;


use vxPHP\Application\Application;
use vxPHP\Constraint\Validator\Ip;
use vxPHP\Service\ServiceInterface;

class BruteforceThrottler implements ServiceInterface
{

    /**
     * @var array
     */
    protected $whiteListedIps = [];

    public function setParameters(array $parameters) {

        if(isset($parameters['whitelisted_ips'])) {

            $validator = new Ip();

            foreach(explode(',', $parameters['whitelisted_ips']) as $ip) {

                if(!$validator->validate(trim($ip))) {
                    throw new \InvalidArgumentException(sprintf("Invalid ip '%s'", $ip));
                }

                $this->whiteListedIps[] = trim($ip);
            }

        }

    }

    public function throttle($ip, $data = null) {

        if($this->checkIpWhiteListing($ip)) {
            return;
        }

        $this->registerAttempt($ip, $data);

    }

    private function checkIpWhiteListing($ip) {

        return in_array($ip, $this->whiteListedIps);

    }

    private function registerAttempt($ip, $data) {

        Application::getInstance()->getDb()->insertRecord('bruteforce_attempts', [
            'ip' => $ip,
            'action' => 'admin_login',
            'when' => time(),
            'data' => $data ? json_encode($data): null,
        ]);

    }

    public function getAttempts($ip, $action = 'admin_login') {

        return Application::getInstance()->getDb()->doPreparedQuery("
          SELECT
            COUNT(*) AS cnt
          FROM
            bruteforce_attempts
          WHERE
            ip = ?
            AND action = ?
        ", [$ip, $action])[0]['cnt'];

    }

}