<?php

error_reporting(E_ALL & ~E_STRICT);
ini_set('display_errors', '1');

require_once 'SplClassLoader.php';
require_once 'CustomClassLoader.php';

$serverRoot = rtrim($_SERVER['DOCUMENT_ROOT'], DIRECTORY_SEPARATOR);

SplClassLoader::create		('vxPHP', $serverRoot . DIRECTORY_SEPARATOR . 'vendor')->register();
SplClassLoader::create		('vxWeb', $serverRoot . DIRECTORY_SEPARATOR . 'vendor')->register();
CustomClassLoader::create	($serverRoot)->register();

session_start();

if(isset($_GET['__clear__session__'])) {
	$_SESSION = array();
}
