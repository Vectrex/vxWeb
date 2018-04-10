<?php

namespace App\Controller\Admin;

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

	protected function execute() {

		$admin = Application::getInstance()->getCurrentUser();

		if($admin && $admin->isAuthenticated()) {

			foreach(Application::getInstance()->getConfig()->routes['admin.php'] as $route) {

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

			$userProvider = new SessionUserProvider();
			$userProvider->unsetSessionUser();

			$values = $form->bindRequestParameters($this->request->request)->getValidFormValues();

			try {
				$admin = $userProvider->instanceUserByUsername($values['UID']);

				if($admin && $admin->authenticate($values['pwd'])->isAuthenticated()) {
					return new JsonResponse(['command' => 'submit']);
				}
			}
			catch(UserException $e) {}

			Application::getInstance()->getService('bruteforce_throttler')->throttle($this->request->getClientIp(), $values->all());

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
