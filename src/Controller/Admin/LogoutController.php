<?php

namespace App\Controller\Admin;

use vxPHP\Controller\Controller;
use vxPHP\Routing\Router;
use vxWeb\User\SessionUserProvider;

class LogoutController extends Controller {

	protected function execute() {

		(new SessionUserProvider())->unsetSessionUser();
		
		return $this->redirect(Router::getRoute('login', 'admin.php')->getUrl());
	}
}
