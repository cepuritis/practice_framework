<?php
require_once __DIR__ . "/globalConstants.php";

spl_autoload_register(function ($class) {
    require_once(__DIR__ . "/../src/" . str_replace('\\', '/', $class) . ".php");
});

use Core\HttpRequest;

$request = HttpRequest::getInstance();