<?php

namespace App\Controller\Admin;

use vxPHP\Controller\Controller;
use vxPHP\User\User;
use vxPHP\Routing\Router;

class LogoutController extends Controller {

	protected function execute() {

		if($admin = User::getSessionUser()) {
			$admin->removeFromSession();
		}

		return $this->redirect(Router::getRoute('login', 'admin.php')->getUrl());
	}
}
