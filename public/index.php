<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once(__DIR__ . "/../config/bootstrap.php");
global $request;

//use Core\Config\Config;
//
//$config = new Config();
//$session = new \Core\User\Session($config);
//$session->set('test_key', 'test_data');
//$session->get('test_key');
//Todo Create Some sort of Object Manager For Dependency Injection
$frontController = new \Core\Routing\FrontController($request);
$frontController->dispatch();
