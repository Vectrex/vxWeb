<?php

$assetsPath		= getcwd();
$rootPath		= $assetsPath;

if(file_exists('site.config.php')) {
	require_once 'site.config.php';
}

else {
	$rootPath = dirname($rootPath);
	require_once '../site.config.php';
}
?>

<?php
/*
use vxPHP\Application\Application;
use vxPHP\Database\vxPDOUtil;
$config = Application::getInstance()->getConfig();


$db = new vxPHP\Database\vxPDO(
	array(
		'host'		=> $config->db->host,
		'dbname'	=> $config->db->name,
		'user'		=> $config->db->user,
		'pass'		=> $config->db->pass,
		'logtype'	=> $config->db->logtype
	)
);

var_dump($db->getEnumValues('test', 'setcol'));
var_dump($db->getPrimaryKey('test'));
var_dump($db->getColumnDefaultValue('test', 'description'));
var_dump($db->getColumnDefaultValue('test', 'signature'));
var_dump($db->getColumnDefaultValue('test', 'alias'));

var_dump($db);