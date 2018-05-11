<?php

$assetsPath		= getcwd();
$rootPath		= $assetsPath;

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

$app = \vxPHP\Application\Application::getInstance();

if(isset($loader)) {
    $loader->addPrefix(
        $app->getApplicationNamespace(),
        $app->getSourcePath()
    );
}

// set up routing

$router = new \vxPHP\Routing\Router($app->getConfig()->routes[trim($_SERVER['PHP_SELF'], '/')]);
$app->setRouter($router);

$route = $router->getRoute('installer');
$app->setCurrentRoute($route);

// render output

\vxPHP\Controller\Controller::createControllerFromRoute($route)->renderResponse();
