<?php
require_once __DIR__ . "/globalConstants.php";

spl_autoload_register(function ($class) {
    require_once(__DIR__ . "/../src/" . str_replace('\\', '/', $class) . ".php");
});

require_once __DIR__ . "/bindings.php";
use Core\Http\HttpRequest;
use Core\User\Session;

app()->make(HttpRequest::class);
app()->make(Session::class);
