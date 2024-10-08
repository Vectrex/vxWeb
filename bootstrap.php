<?php

use vxPHP\Application\Config\DotEnvReader;
use vxPHP\Application\Exception\ConfigException;
use vxWeb\Config\Parser\CustomVxweb;

// enable error reporting (might be disabled at end of bootstrap)

ini_set('display_errors', true);
error_reporting(E_ALL & ~(E_STRICT|E_DEPRECATED));

if(!file_exists($rootPath . '/vendor/autoload.php')) {
    die ('No autoloader found. Please run "composer install".');
}

require_once $rootPath . '/vendor/autoload.php';

// populate $_ENV

if (file_exists($rootPath . '/.env')) {
    (new DotEnvReader($rootPath . '/.env'))->read();
}

$iniPath = $rootPath . DIRECTORY_SEPARATOR . 'ini' . DIRECTORY_SEPARATOR;
$configFilename = $iniPath . 'site.ini.xml';
$cachedConfigPath = $iniPath . '.cache' . DIRECTORY_SEPARATOR;
$cachedConfigFilename = $cachedConfigPath . 'app_config';

// read configuration and cache if necessary

if(!file_exists($configFilename)) {
	die ('No site.ini.xml in ' . $iniPath . ' found.');
}

if(file_exists($cachedConfigFilename)) {

    /*
     * check whether cached file is outdated
     *
     * checks .env file in root path
     * checks all XML files in the ini folder tree (whether they are relevant or not)
     * does not consider any included XML files outside the ini folder
     */
    $cachedFileTimestamp = filemtime($cachedConfigFilename);
    $cachedIsValid = true;

    if (file_exists($rootPath . '/.env') && filemtime($rootPath . '/.env') > $cachedFileTimestamp) {
        $cachedIsValid = false;
    }
    else {
        foreach (
            new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator(
                    $iniPath,
                    FilesystemIterator::SKIP_DOTS
                ),
                RecursiveIteratorIterator::CHILD_FIRST
            ) as $f) {

            if ($f->getMTime() > $cachedFileTimestamp && strtolower($f->getExtension()) === 'xml') {
                $cachedIsValid = false;
                break;
            }
        }
    }

    if($cachedIsValid) {
        $config = unserialize(file_get_contents($cachedConfigFilename), ['allowed_classes' => true]);
    }
}

// cached config was either not found or is outdated 

if(!isset($config)) {

	// create cache path if no cached config was found and path does not exist

	if(!isset($cachedFileTimestamp) && !file_exists($cachedConfigPath) && !mkdir($cachedConfigPath, 0777, true) && !is_dir($cachedConfigPath)) {
        die ('Cannot create directory ' . $cachedConfigPath);
    }

	// create config instance
	
	try {
		$config = new vxPHP\Application\Config($configFilename, ['parsers' => ['custom_vxweb' => CustomVxweb::class]]);
	}
	catch(ConfigException $e) {
		die('<h1>Cannot create Config instance</h1><pre>' . $e->getMessage() . '</pre>');
	}
	
	// write file to cache
	
	if(false === file_put_contents($cachedConfigFilename, serialize($config))) {
		die ('<h1>Cannot create serialized config file</h1>' . $cachedConfigFilename);
	}

}

// initialize application

$application = vxPHP\Application\Application::getInstance($config);

// set path information

$application
	->setRootPath ($rootPath)
	->setAbsoluteAssetsPath ($assetsPath)
    ->setRelativeAssetsPath (dirname($_SERVER['SCRIPT_NAME']))
	->registerPlugins ()
;

// set debugging and error reporting level depending on environment

if(!$application::runsLocally()) {
	vxPHP\Debug\Debug::enable(E_ALL, false);
}
