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

$route = vxPHP\Routing\Router::getRoute('installer', 'installer.php');
$application->setCurrentRoute($route);

// render output

\vxPHP\Controller\Controller::createControllerFromRoute($route)->renderResponse();
