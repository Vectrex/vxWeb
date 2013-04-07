<?php
error_reporting(E_ALL & ~E_STRICT);
ini_set('display_errors', '1');

require_once 'SplClassLoader.php';
require_once 'CustomClassLoader.php';

$splClassLoader = new SplClassLoader('vxPHP', $_SERVER['DOCUMENT_ROOT']);
$splClassLoader->register();

$customClassLoader = new CustomClassLoader();
$customClassLoader->register();

session_start();

if(isset($_GET['__clear__session__'])) {
	$_SESSION = array();
}

try {
	@$config = vxPHP\Config\Config::getInstance('ini/site.ini.xml');
}
catch (vxPHP\Config\Exception\ConfigException $e) {
	printf('
		<div style="border: solid 2px; color: #c00; font-weight: bold; padding: 1em; width: 40em; margin: auto; ">
			Fatal Error!<br>
			Message: %s
		</div>
	', $e->getMessage()
	);
	die();
}

$config->createConst();

$eventDispatcher = vxPHP\Observer\Eventdispatcher::getInstance();

if($config->db) {
	$db = new vxPHP\Database\Mysqldbi();
}

$config->attachPlugins();
?>
