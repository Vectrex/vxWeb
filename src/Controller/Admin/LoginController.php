<?php

namespace App\Controller\Admin;

use App\Service\vxWeb\BruteforceThrottler;
use vxPHP\Http\ParameterBag;
use vxPHP\User\Exception\UserException;
use vxPHP\Application\Application;
use vxPHP\Controller\Controller;
use vxPHP\Http\JsonResponse;
use vxWeb\Session\JWTSession;
use vxWeb\User\SessionUserProvider;

class LoginController extends Controller
{
    protected function execute(): JsonResponse
    {
        $bag = new ParameterBag(json_decode($this->request->getContent(), true, 512, JSON_THROW_ON_ERROR));
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
        catch(UserException) {
            try {
                $admin = $userProvider->instanceUserByEmail($username);
            }
            catch(UserException) {
                $admin = null;
            }
        }

        if($admin && $admin->authenticate($password)->isAuthenticated()) {
            $throttler?->clearAttempts($this->request->getClientIp(), 'admin_login');

            // create new JWT containing session id

            $roles = [];
            foreach ($admin->getRoles() as $role) {
                $roles[] = $role->getRoleName();
            }

            return new JsonResponse([
                'bearerToken' => JWTSession::createToken(),
                'user' => [
                    'username' => $admin->getUsername(),
                    'email' => $admin->getAttribute('email'),
                    'roles' => $roles
                ]
            ]);
        }

        $throttler?->registerAttempt($this->request->getClientIp(), [$username, substr($password, 0, 2) . '...' . substr($password, -2)])->throttle($this->request->getClientIp());

        return new JsonResponse(['error' => true, 'message' => 'Ungültiger Benutzername oder ungültiges Passwort!']);
	}
}
