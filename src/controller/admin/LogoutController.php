<?php

use vxPHP\Controller\Controller;
use vxPHP\User\User;

class LogoutController extends Controller {

	protected function execute() {

		if($admin = User::getSessionUser()) {
			$admin->removeFromSession();
		}

		$this->redirect('login');
	}
}
