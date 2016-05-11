<?php

use vxPHP\User\Exception\UserException;
use vxPHP\Application\Application;
use vxPHP\Session\Session;
use vxPHP\Form\HtmlForm;
use vxPHP\Form\FormElement\FormElementFactory;
use vxPHP\Template\SimpleTemplate;
use vxPHP\Controller\Controller;
use vxPHP\Http\Response;
use vxPHP\Http\JsonResponse;
use vxPHP\User\User;
use vxPHP\Routing\Router;

class LoginController extends Controller {

	protected function execute() {

		$admin = User::getSessionUser();

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
				->addElement(FormElementFactory::create('input',	'UID',	'',	[],	[],	FALSE,	['trim']))
				->addElement(FormElementFactory::create('password',	'pwd',	'',	[]))
				->addElement(FormElementFactory::create('button',	'submit_login',	'',	['type' => 'submit'])->setInnerHTML('Login'));

		// form was submitted by XHR

		if($this->request->getMethod() === 'POST') {

			$values = $form->bindRequestParameters($this->request->request)->getValidFormValues();

			try {
				$admin = User::getInstance($values['UID']);
				$admin->authenticate($values['pwd']);

				if($admin->isAuthenticated()) {
					$admin->storeInSession();
					return new JsonResponse(['command' => 'submit']);
				}
			}
			catch(UserException $e) {}

			return new JsonResponse(['message' => 'Ungültige Email oder ungültiges Passwort!']);

		}

		// non-XHR submission

		else {

			$form->bindRequestParameters();

			if($form->wasSubmittedByName('submit_login')) {

				$values = $form->getValidFormValues();

				try {

					$admin = User::getInstance($values['UID']);
					$admin->authenticate($values['pwd']);

					if(!$admin->isAuthenticated()) {
						$form->setError('submit_login');
					}

					else {

						$admin->storeInSession();
						
						$session = Session::getSessionDataBag();

						if(($redir = $session->get('authViolatingUri'))) {
							$session->remove('authViolatingUri');
							return $this->redirect($redir);
						}
						return $this->redirect(Router::getRoute('profile', 'admin.php')->getUrl());
					}
				}

				catch(UserException $e) {
					$form->setError('submit_login');
				}
			}

			return new Response(
				SimpleTemplate::create('admin/login.php')
					->assign('form', $form->render())
					->display()
			);
		}
	}
}
