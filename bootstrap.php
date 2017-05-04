<?php

// register autoloaders

ini_set('display_errors', TRUE);
error_reporting(E_ALL & ~(E_STRICT|E_DEPRECATED));

// package was installed with composer?

if(file_exists($rootPath . '/vendor/autoload.php')) {

	require_once $rootPath . '/vendor/autoload.php';

}

// if not set up own autoloaders

else {

	require_once $rootPath . '/vendor/vxPHP/Autoload/Psr4.php';
	
	$loader = new vxPHP\Autoload\Psr4();
	$loader->register();
	
	$loader->addPrefix('vxPHP', $rootPath . '/vendor/vxPHP');

}

$iniPath				= $rootPath . DIRECTORY_SEPARATOR . 'ini' . DIRECTORY_SEPARATOR;
$configFilename			= $iniPath . 'site.ini.xml';
$cachedConfigPath		= $iniPath . '.cache' . DIRECTORY_SEPARATOR;
$cachedConfigFilename	= $cachedConfigPath . 'app_config';

// read configuration and cache if necessary

if(!file_exists($configFilename)) {
	die ('No site.ini.xml in ' . $iniPath . ' found.');
}

if(file_exists($cachedConfigFilename)) {

	/*
	 * check whether cached file is outdated
	 * 
	 * checks all XML files in the ini folder tree (whether they are relevant or not)
	 * does not consider any included XML files outside the ini folder
	 */

	
	$cachedFileTimestamp = filemtime($cachedConfigFilename);
	$cachedIsValid = TRUE;

	foreach(
		new \RecursiveIteratorIterator(
			new \RecursiveDirectoryIterator(
				$iniPath,
				\FilesystemIterator::SKIP_DOTS
			),
			\RecursiveIteratorIterator::CHILD_FIRST
		) as $f) {
		
		if(strtolower($f->getExtension()) === 'xml' && $f->getMTime() > $cachedFileTimestamp) {
			$cachedIsValid = FALSE;
			break;
		}
	}
	
	if($cachedIsValid) {
		$config = unserialize(file_get_contents($cachedConfigFilename));
	}

}

// cached config was either not found or is outdated 

if(!isset($config)) {

	// create cache path if no cached config was found and path does not exist

	if(!isset($cachedFileTimestamp)) {

		if(!file_exists($cachedConfigPath)) {
			if(!mkdir($cachedConfigPath, 0777, TRUE)) {
				die ('Cannot create directory ' . $cachedConfigPath);
			}
		}
	}

	// create config instance
	
	try {
		$config = new vxPHP\Application\Config($configFilename);
	}
	
	catch(\vxPHP\Application\Exception\ConfigException $e) {
		die('<h1>Cannot create Config instance</h1><pre>' . $e->getMessage() . '</pre>');
	}
	
	// write file to cache
	
	if(FALSE === file_put_contents($cachedConfigFilename, serialize($config))) {
		die ('<h1>Cannot create serialized config file</h1>' . $cachedConfigFilename);
	}

}

// initialize application

$application = vxPHP\Application\Application::getInstance($config);

// make loader in application available

$application
	->setRootPath			($rootPath)
	->setAbsoluteAssetsPath	($assetsPath)
	->registerPlugins		();

// set debugging and error reporting level depending on environment

if(!$application->runsLocally()) {
	vxPHP\Debug\Debug::enable(E_ALL, FALSE);
}
