<?php
use vxPHP\Controller\Controller;
use vxPHP\Http\JsonResponse;
use vxPHP\Http\Exception\HttpException;
use vxPHP\Http\Request;
use vxPHP\Http\Response;
use vxWeb\Session\JWTSession;

$assetsPath = getcwd();
$rootPath = $assetsPath;

if(file_exists($rootPath . DIRECTORY_SEPARATOR . 'bootstrap.php')) {
	require 'bootstrap.php';
}
else {
	$rootPath = dirname($rootPath);

	if(file_exists($rootPath . DIRECTORY_SEPARATOR . 'bootstrap.php')) {
		require $rootPath . DIRECTORY_SEPARATOR . 'bootstrap.php';
	}
	else {
		die('Cannot load bootstrap code from "' . $rootPath . DIRECTORY_SEPARATOR . 'bootstrap.php".');
	}
}

// place any custom initialisation code here
ini_set('session.use_cookies', 0);

$sessionId = JWTSession::getId(Request::createFromGlobals());

if ($sessionId === false) {
    $sessionId = bin2hex(random_bytes(32));
}

session_id ($sessionId);

require $rootPath . DIRECTORY_SEPARATOR . 'application.php';

// render output

try {
    $route = $router->getRouteFromPathInfo(Request::createFromGlobals());
    $app->setCurrentRoute($route);

    Controller::createControllerFromRoute(
        $route,
        $app->getApplicationNamespace(),
        Request::createFromGlobals()
    )
        ->getResponse()
        ->send()
    ;
}
catch(HttpException $e) {
    (new JsonResponse($e->getMessage(), $e->getStatusCode()))->send();
}
catch(\Exception $e) {
    (new JsonResponse($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR))->send();
}
