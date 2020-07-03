<?php

namespace App\Controller\Admin;

use vxPHP\Application\Application;
use vxPHP\Http\RedirectResponse;
use vxPHP\Controller\Controller;
use vxPHP\Http\Response;
use vxPHP\Http\JsonResponse;

class AuthViolationController extends Controller
{
    protected function execute()
    {
        if($this->request->headers->get('X-Requested-With')) {
            return new JsonResponse(null, Response::HTTP_FORBIDDEN);
        }
        return new RedirectResponse(Application::getInstance()->getRouter()->getRoute('login')->getUrl());
	}
}
