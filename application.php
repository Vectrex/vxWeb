<?php

use vxPHP\Application\Application;

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

// ensure the presence of a valid assets path

if(!is_dir($application->getAbsoluteAssetsPath())) {
	throw new \Exception("Assets path '" . $application->getRelativeAssetsPath() . "' not found.");
}

// parse route

$route = vxPHP\Routing\Router::getRouteFromPathInfo();
$application->setCurrentRoute($route);

// render output

$route->getController()->renderResponse();
