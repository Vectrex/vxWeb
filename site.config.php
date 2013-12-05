<?php

error_reporting(E_ALL & ~E_STRICT);
ini_set('display_errors', '1');

require_once 'SplClassLoader.php';
require_once 'CustomClassLoader.php';

SplClassLoader::create		('vxPHP', $rootPath . DIRECTORY_SEPARATOR . 'vendor')->register();
SplClassLoader::create		('vxWeb', $rootPath . DIRECTORY_SEPARATOR . 'vendor')->register();
CustomClassLoader::create	($rootPath)->register();

session_start();

if(isset($_GET['__clear__session__'])) {
	$_SESSION = array();
}

$application = vxPHP\Application\Application::getInstance($rootPath . DIRECTORY_SEPARATOR . 'ini/site.ini.xml');

$application->setRootPath($rootPath);
$application->setAbsoluteAssetsPath($assetsPath);

if(!is_dir($application->getAbsoluteAssetsPath())) {
	throw new Exception("Assets path '" . $application->getRelativeAssetsPath() . "' not found.");
}
