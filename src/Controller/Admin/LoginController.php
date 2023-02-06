<?php

namespace App\Controller\Admin;

use App\Service\vxWeb\BruteforceThrottler;
use vxPHP\Http\ParameterBag;
use vxPHP\Routing\Route;
use vxPHP\Security\Csrf\CsrfToken;
use vxPHP\Security\Csrf\CsrfTokenManager;
use vxPHP\User\Exception\UserException;
use vxPHP\Application\Application;
use vxPHP\Template\SimpleTemplate;
use vxPHP\Controller\Controller;
use vxPHP\Http\Response;
use vxPHP\Http\JsonResponse;
use vxWeb\Session\JWTSession;
use vxWeb\User\SessionUserProvider;

class LoginController extends Controller
{
    protected function execute()
    {
        $bag = new ParameterBag(json_decode($this->request->getContent(), true));
        $app = Application::getInstance();

        $username = $bag->get('username');
        $password = $bag->get('password');

        /** @var $throttler BruteforceThrottler */

         if($app->hasService('bruteforce_throttler')) {
             $throttler = $app->getService('bruteforce_throttler');
         }
         else {
             $throttler = null;
         }

        $userProvider = new SessionUserProvider();
        $userProvider->unsetSessionUser();

        try {
            $admin = $userProvider->instanceUserByUsername($username);
        }
        catch(UserException $e) {
            try {
                $admin = $userProvider->instanceUserByEmail($username);
            }
            catch(UserException $e) {
                $admin = null;
            }
        }

        if($admin && $admin->authenticate($password)->isAuthenticated()) {
            if($throttler) {
                $throttler->clearAttempts($this->request->getClientIp(), 'admin_login');
            }

            // create new JWT containing session id

            return new JsonResponse(['bearerToken' => JWTSession::createToken(), 'username' => $admin->getUsername()]);
        }

        if($throttler) {
            $throttler->registerAttempt($this->request->getClientIp(), [$username, substr($password, 0, 2) . '...' . substr($password, -2)])->throttle($this->request->getClientIp(), 'admin_login');
        }

        return new JsonResponse(['error' => true, 'message' => 'Ungültiger Benutzername oder ungültiges Passwort!']);
	}
}
