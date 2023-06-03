<?php

use vxWeb\User\vxWebRoleHierarchy;
use vxWeb\User\SessionUserProvider;
use vxPHP\Application\Application;
use vxPHP\Http\Response;
use vxPHP\Routing\Router;
use vxPHP\Http\Request;

// return empty response for OPTION request

if (Request::createFromGlobals()->getMethod() === 'OPTIONS') {
    return new Response();
}

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
