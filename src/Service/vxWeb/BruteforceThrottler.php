<?php

namespace App\Service\vxWeb;


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

    public function throttle($ip) {
    }
}