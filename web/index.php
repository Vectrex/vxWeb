<?php
use vxPHP\Http\Response;
use vxPHP\Template\SimpleTemplate;
use vxPHP\Template\Exception\SimpleTemplateException;
use vxPHP\Http\Exception\HttpException;
use vxPHP\Http\Request;
use vxPHP\Controller\Controller;

$assetsPath = getcwd();
$rootPath = $assetsPath;

header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: authorization,content-type");

if(file_exists($rootPath . DIRECTORY_SEPARATOR . 'bootstrap.php')) {
	require 'bootstrap.php';
}

else {
	$rootPath = dirname($rootPath);

	if(file_exists($rootPath . DIRECTORY_SEPARATOR . 'bootstrap.php')) {
		require $rootPath . DIRECTORY_SEPARATOR . 'bootstrap.php';
	}
	else {
		die('Cannot load bootstrap code from "' . $rootPath . DIRECTORY_SEPARATOR . 'bootstrap.php".');
	}
}

// place any custom initialisation code here

require $rootPath . DIRECTORY_SEPARATOR . 'application.php';

// render output

try {
    $route = $router->getRouteFromPathInfo(Request::createFromGlobals());
    $app->setCurrentRoute($route);

    Controller::createControllerFromRoute(
        $route,
        $app->getApplicationNamespace(),
        Request::createFromGlobals()
    )
        ->getResponse()
        ->send()
    ;
}
catch(HttpException $e) {
    try {
        $tpl = SimpleTemplate::create('errordocs/' . $e->getStatusCode() . '.php')->assign('exception', $e);
    }
    catch(SimpleTemplateException $ste) {
        try {
            $tpl = SimpleTemplate::create('errordocs/catchall.php')->assign('exception', $e);
        }
        catch(SimpleTemplateException $ste) {
            $tpl = SimpleTemplate::create()->setRawContents('<h1>' . $e->getStatusCode() . '</h1>');
        }
    }

    (new Response($tpl->display(), $e->getStatusCode()))->send();
}
catch(\Exception $e) {
    (new Response(
        SimpleTemplate::create()
            ->setRawContents('<h1>' . $e->getMessage() . '</h1>')
            ->display()
        , Response::HTTP_INTERNAL_SERVER_ERROR
    ))->send();
}
