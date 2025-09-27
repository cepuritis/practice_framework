<?php

if (!function_exists('app')) {
    function app(): \Core\App\Application
    {
        return \Core\App\Application::getInstance();
    }
}