<?php

$assetsPath = getcwd();
$rootPath = $assetsPath;

header("Access-Control-Allow-Headers: authorization,content-type,x-file-name,x-file-size,x-file-type");
header("Access-Control-Allow-Methods: GET,HEAD,POST,PUT,DELETE");

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
ini_set('session.use_cookies', 0);

$sessionId = \vxWeb\Session\JWTSession::getId(\vxPHP\Http\Request::createFromGlobals());

if ($sessionId === false) {
    $sessionId = bin2hex(random_bytes(32));
}

session_id ($sessionId);

require $rootPath . DIRECTORY_SEPARATOR . 'application.php';
