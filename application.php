<?php

// $loader is initialized in bootstrap.php
// place additional libraries here

$loader->addPrefix('vxWeb', $rootPath . '/vendor/vxWeb');

// ensure the presence of a valid assets path

if(!is_dir($application->getAbsoluteAssetsPath())) {
	throw new \Exception("Assets path '" . $application->getRelativeAssetsPath() . "' not found.");
}

// parse route

$route = vxPHP\Routing\Router::getRouteFromPathInfo();
$application->setCurrentRoute($route);

// render output

$route->getController()->renderResponse();
