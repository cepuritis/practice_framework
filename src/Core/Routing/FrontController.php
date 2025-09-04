<?php

namespace Core\Routing;

use Core\Contracts\Http\HttpRequestInterface;
use Core\Contracts\RouterInterface;

class FrontController
{
    private HttpRequestInterface $request;
    public function __construct(HttpRequestInterface $request)
    {
        $this->request = $request;
    }
    public function dispatch()
    {
        /**
         * @var RouterInterface $router
         */
        foreach ($this->getAllRouters() as $router) {
            if ($router->match($this->request->getPath(), $this->request->getMethod())) {
                $router->dispatch($this->request);
            }
        }
    }

    /**
     * @return array<string>
     */
    private function getAllRouters(): array
    {
        $routers = require CONFIG_PATH . "/generated/routes.php";

        $routerInstances = [];

        foreach ($routers as $router => $paths) {
            $routerInstances[] = new $router($paths);
        }

        return $routerInstances;
    }
}
