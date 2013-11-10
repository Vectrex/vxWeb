<?php

require_once 'site.config.php';

$route = vxPHP\Http\Router::getRouteFromPathInfo();
vxPHP\Application\Application::getInstance()->setCurrentRoute($route);
$controller = $route->getController()->render();

?>