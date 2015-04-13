<?php

// register autoloaders

require_once $rootPath . '/vendor/vxPHP/Autoload/Psr4.php';

$loader = new vxPHP\Autoload\Psr4();
$loader->register();

$loader->addPrefix('vxPHP', $rootPath . '/vendor/vxPHP');
$loader->addPrefix('vxWeb', $rootPath . '/vendor/vxWeb');

$iniPath				= $rootPath . DIRECTORY_SEPARATOR . 'ini' . DIRECTORY_SEPARATOR;
$configFilename			= $iniPath . 'site.ini.xml';
$cachedConfigPath		= $iniPath . '.cache' . DIRECTORY_SEPARATOR;
$cachedConfigFilename	= $cachedConfigPath . 'app_config';

// read configuration and cache if necessary

if(!file_exists($configFilename)) {
	throw new \Exception('No site.ini.xml in ' . $iniPath . ' found.');
}

if(file_exists($cachedConfigFilename)) {

	if(filemtime($cachedConfigFilename) > filemtime($configFilename)) {
		$config = unserialize(file_get_contents($cachedConfigFilename));
	}
	else {
		$config = new vxPHP\Application\Config($configFilename);
		file_put_contents($cachedConfigFilename, serialize($config));
	}

}

else {

	$config = new vxPHP\Application\Config($configFilename);

	// create path

	if(!file_exists($cachedConfigPath)) {
		if(!mkdir($cachedConfigPath, 0777, TRUE)) {
			throw new \Exception('Cannot create directory ' . $cachedConfigPath);
		}
	}

	// create file

	if(FALSE === file_put_contents($cachedConfigFilename, serialize($config))) {
		throw new \Exception('Cannot create serialized config file ' . $cachedConfigFilename);
	}
}

// initialize application

$application = vxPHP\Application\Application::getInstance($config);

$application->setRootPath			($rootPath);
$application->setAbsoluteAssetsPath	($assetsPath);

// set debugging and error reporting level depending on environment

if($application->runsLocally()) {
	ini_set('display_errors', TRUE);
}
else {
	\vxPHP\Debug\Debug::enable(E_ALL, FALSE);
}