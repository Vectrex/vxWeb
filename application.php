<?php

if(!is_dir($application->getAbsoluteAssetsPath())) {
	throw new \Exception("Assets path '" . $application->getRelativeAssetsPath() . "' not found.");
}

// parse route

$route = vxPHP\Routing\Router::getRouteFromPathInfo();
$application->setCurrentRoute($route);

// render output

$route->getController()->renderResponse();
