<?php
define('CONFIG_PATH', __DIR__);
define('ROUTER_PATH', realpath(__DIR__ . "/../src/Core/Routing/Routers"));
define('CONTROLLER_PATH', realpath(__DIR__ . "/../src/Controller"));
define('VIEW_PATH', realpath(__DIR__ . "/../src/View"));
define('INTERNAL_ASSETS_PATH', realpath(__DIR__ . "/../src/View/assets"));
define('PUBLIC_ASSETS_PATH', "/build");
define('CSRF_TOKEN_BITS', 128);
