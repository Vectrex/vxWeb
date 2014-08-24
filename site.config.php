<?php

error_reporting(E_ALL & ~E_STRICT);
ini_set('display_errors', '1');

require_once $rootPath . '/vendor/vxPHP/Autoload/Psr4.php';

$loader = new vxPHP\Autoload\Psr4();
$loader->register();

$loader->addPrefix('vxPHP', $rootPath . '/vendor/vxPHP');
$loader->addPrefix('vxWeb', $rootPath . '/vendor/vxWeb');

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
