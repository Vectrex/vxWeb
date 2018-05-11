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

// if autoloading is not handled by composer, add namespaces explicitly 

if(isset($loader)) {

	$loader->addPrefix(
		'vxWeb',
		$app->getRootPath() . 'vendor/vxWeb'
	);
	
	$loader->addPrefix(
		$app->getApplicationNamespace(),
		$app->getSourcePath()
	);

}

// set role hierarchy

$app->setRoleHierarchy(new vxWebRoleHierarchy());

// set current user; might be required for route and menu authentication

if($currentUser = (new SessionUserProvider())->getSessionUser()) {
	$app->setCurrentUser($currentUser);
}

// ensure the presence of a valid assets path

if(!is_dir($application->getAbsoluteAssetsPath())) {
	throw new \Exception(sprintf("Assets path '%s' not found.", $application->getRelativeAssetsPath()));
}

// set up routing

$scriptName = trim($_SERVER['PHP_SELF'], '/');
$router = new Router(Application::getInstance()->getConfig()->routes[$scriptName]);
Application::getInstance()->setRouter($router);

$route = $router->getRouteFromPathInfo(vxPHP\Http\Request::createFromGlobals());
$application->setCurrentRoute($route);

// render output

try {
    Controller::createControllerFromRoute($route)->renderResponse();
}
catch(HttpException $e) {

    try {
        $tpl = SimpleTemplate::create('errordocs/' . $e->getStatusCode() . '.php');
    }
    catch(SimpleTemplateException $ste) {
        $tpl = SimpleTemplate::create()->setRawContents('<h1>' . $e->getStatusCode() . '</h1>');
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
