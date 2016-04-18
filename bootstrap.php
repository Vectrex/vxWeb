<?php

// register autoloaders

require_once $rootPath . '/vendor/vxPHP/Autoload/Psr4.php';

$loader = new vxPHP\Autoload\Psr4();
$loader->register();

$loader->addPrefix('vxPHP', $rootPath . '/vendor/vxPHP');

$iniPath				= $rootPath . DIRECTORY_SEPARATOR . 'ini' . DIRECTORY_SEPARATOR;
$configFilename			= $iniPath . 'site.ini.xml';
$cachedConfigPath		= $iniPath . '.cache' . DIRECTORY_SEPARATOR;
$cachedConfigFilename	= $cachedConfigPath . 'app_config';

// read configuration and cache if necessary

if(!file_exists($configFilename)) {
	die ('No site.ini.xml in ' . $iniPath . ' found.');
}

if(file_exists($cachedConfigFilename)) {

	// check whether cached file is outdated

	if(filemtime($cachedConfigFilename) > filemtime($configFilename)) {
		$config = unserialize(file_get_contents($cachedConfigFilename));
	}
	else {

		// create config instance

		try {
			$config = new vxPHP\Application\Config($configFilename);
		}
		
		catch(\vxPHP\Application\Exception\ConfigException $e) {
			die('Cannot create Config instance: ' . $e->getMessage());
		}

		// write file to cache

		if(FALSE === file_put_contents($cachedConfigFilename, serialize($config))) {
			die ('Cannot create serialized config file ' . $cachedConfigFilename);
		}
	}

}

else {

	$config = new vxPHP\Application\Config($configFilename);

	// create path

	if(!file_exists($cachedConfigPath)) {
		if(!mkdir($cachedConfigPath, 0777, TRUE)) {
			die ('Cannot create directory ' . $cachedConfigPath);
		}
	}

	// create file

	if(FALSE === file_put_contents($cachedConfigFilename, serialize($config))) {
		die ('Cannot create serialized config file ' . $cachedConfigFilename);
	}
}

// initialize application

$application = vxPHP\Application\Application::getInstance($config);

// make loader in application available

$application
	->setLoader				($loader)
	->setRootPath			($rootPath)
	->setAbsoluteAssetsPath	($assetsPath)
	->registerPlugins		();

// set debugging and error reporting level depending on environment

if($application->runsLocally()) {
	ini_set('display_errors', TRUE);
}
else {
	vxPHP\Debug\Debug::enable(E_ALL, FALSE);
}