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

// place any custom initialisation code here

//vxPHP\Debug\Debug::enable(E_ALL);
error_reporting(E_ALL);
ini_set('display_errors', 1);

require $rootPath . DIRECTORY_SEPARATOR . 'application.php';
