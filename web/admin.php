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
