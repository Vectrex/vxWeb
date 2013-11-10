<?php

use vxPHP\User\Admin;
use vxPHP\Controller\Controller;

class LogoutController extends Controller {

	protected function execute() {

		$admin = Admin::getInstance();

		$admin->removeFromSession();
		$this->redirect('login');
	}
}
