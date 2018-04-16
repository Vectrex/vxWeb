<?php

namespace App\Controller\Admin;

use App\Service\vxWeb\BruteforceThrottler;
use vxPHP\Routing\Route;
use vxPHP\User\Exception\UserException;
use vxPHP\Application\Application;
use vxPHP\Form\HtmlForm;
use vxPHP\Form\FormElement\FormElementFactory;
use vxPHP\Template\SimpleTemplate;
use vxPHP\Controller\Controller;
use vxPHP\Http\Response;
use vxPHP\Http\JsonResponse;
use vxWeb\User\SessionUserProvider;

class LoginController extends Controller {

    /**
     * @return JsonResponse|\vxPHP\Http\RedirectResponse|Response
     */
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

		$form =
			HtmlForm::create('admin_login.htm')
				->addElement(FormElementFactory::create('input',	'UID',	'',	[],	[],	TRUE, ['trim']))
				->addElement(FormElementFactory::create('password',	'pwd',	'', [], [], TRUE, ['trim']))
				->addElement(FormElementFactory::create('button',	'submit_login',	'',	['type' => 'submit'])->setInnerHTML('Login'));

		// form was submitted by XHR

		if($this->request->getMethod() === 'POST') {

             /** @var $throttler BruteforceThrottler */

             if($app->hasService('bruteforce_throttler')) {
                 $throttler = $app->getService('bruteforce_throttler');
             }
             else {
                 $throttler = null;
             }

            $userProvider = new SessionUserProvider();
			$userProvider->unsetSessionUser();

			$values = $form->bindRequestParameters($this->request->request)->getValidFormValues();

			try {
				$admin = $userProvider->instanceUserByUsername($values['UID']);

				if($admin && $admin->authenticate($values['pwd'])->isAuthenticated()) {

				    if($throttler) {
                        $throttler->clearAttempts($this->request->getClientIp(), 'admin_login');
                    }

					return new JsonResponse(['command' => 'submit']);

				}
			}
			catch(UserException $e) {}

			if($throttler) {
                $throttler->registerAttempt($this->request->getClientIp(), $values->all())->throttle($this->request->getClientIp(), 'admin_login');
            }

			return new JsonResponse(['message' => 'Ungültiger Benutzername oder ungültiges Passwort!']);

		}

		else {

			return new Response(
				SimpleTemplate::create('admin/login.php')
					->assign('form', $form->render())
					->display()
			);
		}
	}
}
