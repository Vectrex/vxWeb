<?php

$assetsPath = getcwd();
$rootPath = $assetsPath;

header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: authorization,content-type");

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

require $rootPath . DIRECTORY_SEPARATOR . 'application.php';
