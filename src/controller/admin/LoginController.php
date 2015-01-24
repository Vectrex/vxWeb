<?php

use vxPHP\User\Exception\UserException;
use vxPHP\Application\Application;
use vxPHP\Form\HtmlForm;
use vxPHP\Form\FormElement\FormElementFactory;
use vxPHP\Template\SimpleTemplate;
use vxPHP\Controller\Controller;
use vxPHP\Http\Response;
use vxPHP\Http\JsonResponse;
use vxPHP\User\User;

class LoginController extends Controller {

	protected function execute() {

		$admin = User::getSessionUser();

		if($admin && $admin->isAuthenticated()) {

			foreach(Application::getInstance()->getConfig()->routes['admin.php'] as $route) {

				if(in_array($route->getRouteId(), array('login', 'logout'))) {
					continue;
				}

				$this->redirect($route->getRouteId());
				break;
			}
		}

		$form =
			HtmlForm::create('admin_login.htm')
				->addElement(FormElementFactory::create('input',	'UID',	'',	array('maxlength' => 128, 'class' => 'pct_100'),	array(),	FALSE,	array('trim')))
				->addElement(FormElementFactory::create('password',	'pwd',	'',	array('maxlength' => 128, 'class' => 'pct_100')))
				->addElement(FormElementFactory::create('button',	'submit_login',	'',	array('type' => 'submit', 'class' => '', 'data-icon' => '&#xe02e;', 'data-throbber-position' => 'outside-left'))->setInnerHTML('Login'));

		// form was submitted by XHR

		if($this->request->getMethod() === 'POST') {

			$values = $form->bindRequestParameters($this->request->request)->getValidFormValues();

			try {
				$admin = User::getInstance($values['UID']);
				$admin->authenticate($values['pwd']);

				if($admin->isAuthenticated()) {
					$admin->storeInSession();
					return new JsonResponse(array('command' => 'submit'));
				}
			}
			catch(UserException $e) {}

			return new JsonResponse(array('message' => 'Ungültige Email oder ungültiges Passwort!'));

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
						
						if(isset($_SESSION['authViolatingUri'])) {
							$redir = $_SESSION['authViolatingUri'];
							$_SESSION['authViolatingUri'] = NULL;
							$this->redirect($redir);
						}
						$this->redirect('profile');
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
