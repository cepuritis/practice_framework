<?php
header("Access-Control-Allow-Origin: *");
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require __DIR__.'/../vendor/autoload.php';
require_once(__DIR__ . "/../config/bootstrap.php");
global $request;

use Core\Config\Config;
$session = app()->get(\Core\User\Session::class);
$csrfToken = app()->make(\Core\Security\CsrfTokenManager::class);
$session->set('test_key', 'This is session data set in index.php and stored in redis');

$frontController = app()->get(\Core\Routing\FrontController::class);
$frontController->dispatch();

app()->getInstances();