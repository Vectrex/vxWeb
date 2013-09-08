<?php

use vxPHP\User\Admin;
use vxPHP\User\Exception\UserException;
use vxPHP\Form\HtmlForm;
use vxPHP\Form\FormElement\FormElementFactory;
use vxPHP\Template\SimpleTemplate;

class admin_login_page extends page {

	public function __construct() {

		parent::__construct();

		$admin = Admin::getInstance();

		if($this->route->getRouteId() == 'logout') {
			$admin->removeFromSession();
			$this->redirect();
		}

		if($admin->isAuthenticated()) {
			$this->redirect('profile');
		}

		$this->form = new HtmlForm('admin_login.htm');

		$this->form->addElement(FormElementFactory::create('input',		'UID',	'',	array('maxlength' => 128, 'class' => 'l'),	array(),	FALSE,	array('trim')));
		$this->form->addElement(FormElementFactory::create('password',	'pwd',	'',	array('maxlength' => 128, 'class' => 'l')));

		$button = FormElementFactory::create('button',	'submit_login',	'',	array('type' => 'submit', 'class' => 'm loginButton'));
		$button->setInnerHTML('Login');
		$this->form->addElement($button);

		$this->form->bindRequestParameters();

		if($this->form->wasSubmittedByName('submit_login')) {
			$values = array_map(array($this->db, 'escapeString'), $this->form->getValidFormValues());

			try {
				$admin->setUser($values['UID']);
				$admin->authenticate($values['pwd']);

				if(!$admin->isAuthenticated()) {
					$this->form->setError('submit_login');
					return;
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
				$this->form->setError('submit_login');
				return;
			}
		}
	}

	public function content() {
		$tpl = new SimpleTemplate('admin_login.htm');
		$tpl->assign('form', $this->form->render());
		$html = $tpl->display();
		$this->html .= $html;
		return $html;
	}

	protected function handleHttpRequest() {

		$this->request->request->add($this->request->request->get('elements'));

		$this->form = new HtmlForm('admin_login.htm');
		$this->form->addElement(FormElementFactory::create('input',		'UID', '', array(), array(), FALSE, array('trim')));
		$this->form->addElement(FormElementFactory::create('password',	'pwd'));

		$this->form->bindRequestParameters($this->request->request);

		$values = array_map(array($this->db, 'escapeString'), $this->form->getValidFormValues());

		$admin = Admin::getInstance();

		try {
			$admin->setUser($values['UID']);
			$admin->authenticate($values['pwd']);

			if($admin->isAuthenticated()) {
				return array('command' => 'submit');
			}
		}
		catch(UserException $e) {
			return array('message' => 'Ungültige Email oder ungültiges Passwort!');
		}

		return array('message' => 'Ungültige Email oder ungültiges Passwort!');
	}
}
?>
