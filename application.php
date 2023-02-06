<?php

use vxWeb\User\vxWebRoleHierarchy;
use vxWeb\User\SessionUserProvider;
use vxPHP\Application\Application;
use vxPHP\Controller\Controller;
use vxPHP\Http\Response;
use vxPHP\Http\Exception\HttpException;
use vxPHP\Template\SimpleTemplate;
use vxPHP\Template\Exception\SimpleTemplateException;
use vxPHP\Routing\Router;

// $loader is initialized in bootstrap.php
// place additional libraries here

$app = Application::getInstance();

// set role hierarchy

$app->setRoleHierarchy(new vxWebRoleHierarchy());

// set current user; might be required for route and menu authentication

if($currentUser = (new SessionUserProvider())->getSessionUser()) {
	$app->setCurrentUser($currentUser);
}

// ensure the presence of a valid assets path

if(!is_dir($app->getAbsoluteAssetsPath())) {
	throw new \Exception(sprintf("Assets path '%s' not found.", $app->getRelativeAssetsPath()));
}

// set up routing

$scriptName = basename($_SERVER['SCRIPT_NAME']);

$router = new Router($app->getConfig()->routes[$scriptName]);
$app->setRouter($router);

$route = $router->getRouteFromPathInfo(vxPHP\Http\Request::createFromGlobals());
$app->setCurrentRoute($route);

// render output

try {
    $response = Controller::createControllerFromRoute(
        $route,
        $app->getApplicationNamespace(),
        \vxPHP\Http\Request::createFromGlobals()
    )->getResponse();

    $response->send();
}
catch(HttpException $e) {
    try {
        $tpl = SimpleTemplate::create('errordocs/' . $e->getStatusCode() . '.php')->assign('exception', $e);
    }
    catch(SimpleTemplateException $ste) {
        try {
            $tpl = SimpleTemplate::create('errordocs/catchall.php')->assign('exception', $e);
        }
        catch(SimpleTemplateException $ste) {
            $tpl = SimpleTemplate::create()->setRawContents('<h1>' . $e->getStatusCode() . '</h1>');
        }
    }

    (new Response($tpl->display(), $e->getStatusCode()))->send();
}
catch(\Exception $e) {
    (new Response(
        SimpleTemplate::create()
            ->setRawContents('<h1>' . $e->getMessage() . '</h1>')
            ->display()
        , Response::HTTP_INTERNAL_SERVER_ERROR
    ))->send();
}
