<?php

use vxPHP\User\Admin;
use vxPHP\User\Exception\UserException;
use vxPHP\Application\Application;
use vxPHP\Form\HtmlForm;
use vxPHP\Form\FormElement\FormElementFactory;
use vxPHP\Template\SimpleTemplate;
use vxPHP\Controller\Controller;
use vxPHP\Http\Response;
use vxPHP\Http\JsonResponse;

class LoginController extends Controller {

	protected function execute() {

		$admin = Admin::getInstance();

		if($admin->isAuthenticated()) {

			foreach(Application::getInstance()->getConfig()->routes['admin.php'] as $route) {

				if(in_array($route->getRouteId(), array('login', 'logout'))) {
					continue;
				}

				$this->redirect($route->getRouteId());
				break;
			}
		}

		$button = FormElementFactory::create('button',	'submit_login',	'',	array('type' => 'submit', 'class' => 'm loginButton'));
		$button->setInnerHTML('Login');

		$form =
			HtmlForm::create('admin_login.htm')
				->addElement($button)
				->addElement(FormElementFactory::create('input',	'UID',	'',	array('maxlength' => 128, 'class' => 'l'),	array(),	FALSE,	array('trim')))
				->addElement(FormElementFactory::create('password',	'pwd',	'',	array('maxlength' => 128, 'class' => 'l')));

		// form was submitted by XHR

		if($this->isXhr) {

			$this->request->request->add($this->request->request->get('elements'));
			$values = $form->bindRequestParameters($this->request->request)->getValidFormValues();

			try {
				$admin->setUser($values['UID']);
				$admin->authenticate($values['pwd']);

				if($admin->isAuthenticated()) {
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
					$admin->setUser($values['UID']);
					$admin->authenticate($values['pwd']);

					if(!$admin->isAuthenticated()) {
						$form->setError('submit_login');
					}

					else {
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