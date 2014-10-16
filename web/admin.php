<?php

$assetsPath		= getcwd();
$rootPath		= $assetsPath;

if(file_exists('site.config.php')) {
	require_once 'site.config.php';
}

else {
	$rootPath = dirname($rootPath);
	require_once '../site.config.php';
}


session_start();

if(isset($_GET['__clear__session__'])) {
	$_SESSION = array();
}

$route = vxPHP\Routing\Router::getRouteFromPathInfo();
vxPHP\Application\Application::getInstance()->setCurrentRoute($route);
$route->getController()->render();