<?php
define('CONFIG_PATH', __DIR__);
define('ROUTER_PATH', __DIR__ . "/../src/Core/Routing/Routers");
define('CONTROLLER_PATH', __DIR__ . "/../src/Controller");

spl_autoload_register(function($class) {
    require_once(__DIR__ . "/../src/" . str_replace('\\','/', $class) . ".php");
});

use Core\HttpRequest;

$request = HttpRequest::getInstance();