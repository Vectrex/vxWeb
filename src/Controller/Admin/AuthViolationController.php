<?php

namespace App\Controller\Admin;

use vxPHP\Controller\Controller;
use vxPHP\Http\Response;
use vxPHP\Http\JsonResponse;

class AuthViolationController extends Controller
{
    protected function execute(): JsonResponse
    {
        return new JsonResponse(null, Response::HTTP_UNAUTHORIZED);
	}
}
