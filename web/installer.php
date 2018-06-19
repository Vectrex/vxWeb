<?php
// assume that script resides in "public" subdir

$assetsPath = getcwd();
$rootPath = dirname($assetsPath);

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

$iniPath = $rootPath . DIRECTORY_SEPARATOR . 'ini' . DIRECTORY_SEPARATOR;
$configFilename = $iniPath . 'site.ini.xml';

// read configuration and cache if necessary

if(!file_exists($configFilename)) {
	die ('No site.ini.xml in ' . $iniPath . ' found.');
}

// create config instance

try {
	$config = new vxPHP\Application\Config($configFilename);
}
catch(\vxPHP\Application\Exception\ConfigException $e) {
	die('<h1>Cannot create Config instance</h1><pre>' . $e->getMessage() . '</pre>');
}

// initialize application

$app = vxPHP\Application\Application::getInstance($config);

$app
	->setRootPath			($rootPath)
	->setAbsoluteAssetsPath	($assetsPath)
;

// set debugging and error reporting level depending on environment

if(!$app->runsLocally()) {
	vxPHP\Debug\Debug::enable(E_ALL, FALSE);
}

// without composer install add application namespace for autoloader

if(isset($loader)) {
    $loader->addPrefix(
        $app->getApplicationNamespace(),
        $app->getSourcePath()
    );
}

// set up routing

$router = new \vxPHP\Routing\Router($app->getConfig()->routes[trim($_SERVER['PHP_SELF'], '/')]);
$app->setRouter($router);

$route = $router->getRoute('installer');
$app->setCurrentRoute($route);

// render output

\vxPHP\Controller\Controller::createControllerFromRoute($route)->renderResponse();
