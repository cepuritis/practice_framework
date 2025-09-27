<?php
require_once __DIR__ . "/../config/globalConstants.php";

spl_autoload_register(function ($class) {
    if (str_starts_with($class, 'Practice\\Tests')) {
        $class = substr($class, strlen("Practice\\Tests\\"));
        require_once(__DIR__ . "/" . str_replace("\\", "/", $class) . ".php");
    } else {
        require_once(__DIR__ . "/../src/" . str_replace('\\', '/', $class) . ".php");
    }
});

require_once CONFIG_PATH . "/bindings.php";
