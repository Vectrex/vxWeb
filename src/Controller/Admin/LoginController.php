<?php

namespace App\Controller\Admin;

use App\Service\vxWeb\BruteforceThrottler;
use vxPHP\Http\ParameterBag;
use vxPHP\Routing\Route;
use vxPHP\User\Exception\UserException;
use vxPHP\Application\Application;
use vxPHP\Template\SimpleTemplate;
use vxPHP\Controller\Controller;
use vxPHP\Http\Response;
use vxPHP\Http\JsonResponse;
use vxWeb\User\SessionUserProvider;

class LoginController extends Controller {

    protected function execute() {

        $app = Application::getInstance();
		$admin = $app->getCurrentUser();

		if($admin && $admin->isAuthenticated()) {

			foreach($app->getConfig()->routes['admin.php'] as $route) {

			    /* @var $route Route */

				if(in_array($route->getRouteId(), ['login', 'logout'])) {
					continue;
				}

				return $this->redirect($route->getUrl());
				break;
			}
		}

		// form was submitted by XHR

		if($this->request->getMethod() === 'POST') {

            $bag = new ParameterBag(json_decode($this->request->getContent(), true));
            $app = Application::getInstance();

            $username = $bag->get('username');
            $password = $bag->get('pwd');

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

				if($admin && $admin->authenticate($password)->isAuthenticated()) {

				    if($throttler) {
                        $throttler->clearAttempts($this->request->getClientIp(), 'admin_login');
                    }

					return new JsonResponse(['locationHref' => $this->request->getSchemeAndHttpHost() . $app->getRouter()->getRoute('profile')->getUrl()]);

				}
			}
			catch(UserException $e) {}

			if($throttler) {
                $throttler->registerAttempt($this->request->getClientIp(), [$username, $password])->throttle($this->request->getClientIp(), 'admin_login');
            }

			return new JsonResponse(['error' => true, 'message' => 'Ungültiger Benutzername oder ungültiges Passwort!']);

		}

		else {

			return new Response(SimpleTemplate::create('admin/login.php')->display());

		}
	}
}
