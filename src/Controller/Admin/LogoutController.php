<?php

namespace App\Controller\Admin;

use vxPHP\Application\Application;
use vxPHP\Controller\Controller;
use vxPHP\Routing\Router;
use vxWeb\User\SessionUserProvider;

class LogoutController extends Controller {

	protected function execute()
    {
		(new SessionUserProvider())->unsetSessionUser();
		return $this->redirect(
            urldecode($this->getRequest()->query->get('goto')) ?:
            Application::getInstance()->getRouter()->getRoute('login')->getUrl()
        );
	}
}
