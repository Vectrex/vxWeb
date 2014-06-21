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

//$conf = HTMLPurifier_Config::createDefault();
//$conf->set('HTML.Doctype', 'HTML 4.01 Strict');
//$conf->set('HTML.Allowed', 'a[href], p, ul, ol, li, strong, em, sup, sub, br');
//$purifier = new HTMLPurifier($conf);

$route = vxPHP\Routing\Router::getRouteFromPathInfo();
vxPHP\Application\Application::getInstance()->setCurrentRoute($route);
$controller = $route->getController()->render();
