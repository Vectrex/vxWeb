<?php

error_reporting(E_ALL & ~E_STRICT);
ini_set('display_errors', '1');

require_once 'SplClassLoader.php';
require_once 'CustomClassLoader.php';

$splClassLoader = new SplClassLoader('vxPHP', rtrim($_SERVER['DOCUMENT_ROOT'], DIRECTORY_SEPARATOR));
$splClassLoader->register();

$customClassLoader = new CustomClassLoader(rtrim($_SERVER['DOCUMENT_ROOT'], DIRECTORY_SEPARATOR));
$customClassLoader->register();

session_start();

if(isset($_GET['__clear__session__'])) {
	$_SESSION = array();
}
