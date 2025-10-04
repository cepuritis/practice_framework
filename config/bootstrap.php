<?php
require_once __DIR__ . "/globalConstants.php";

spl_autoload_register(function ($class) {
    require_once(__DIR__ . "/../src/" . str_replace('\\', '/', $class) . ".php");
});

require_once __DIR__ . "/bindings.php";
use Core\Http\HttpRequest;
use Core\User\Session;
use Core\Config\Config;

/** @var Config $config */
$config = app()->get(Config::class);
app()->make(Session::class);

app()->make(HttpRequest::class);

if ($config->getDatabase()) {
    app()->make(\Core\Database\DatabaseConnection::class);
}