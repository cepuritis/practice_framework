<?php

namespace Core\Routing\Routers;

use Core\Contracts\BaseRouter;
use Core\Contracts\Http\HttpRequestInterface;
use Core\Contracts\Http\HttpRequestMethod;
use Core\Routing\Traits\RouteGenerator;

class DefaultRouter extends BaseRouter
{
    public const ROUTER_NAME = 'default';

    use RouteGenerator;
    public const CONTROLLER_DIR = 'src/Controller';
    public const CONTROLLER_PARENT = 'src/';

    public array $routes = [];
    public array $current = [];


    /**
     * @param array $routes
     */
    public function __construct(array $routes)
    {
        parent::__construct($routes);
    }
    /**
     * @param HttpRequestInterface $request
     * @return void
     */
    public function dispatch(HttpRequestInterface $request): void
    {
        $controller = new $this->current[0]();
        $method = $this->current[1];
        $controller->$method($request);
    }

    /**
     * @return bool
     */
    public function match(string $path, HttpRequestMethod $method): bool
    {
        $path = strtolower($path);
        if (in_array($path, array_keys($this->routes))) {
            foreach ($this->routes[$path]['methods'] as $routeMethod => $classMethod) {
                if (HttpRequestMethod::fromString($routeMethod) === $method) {
                    $this->current[] = $this->routes[$path]['class'];
                    $this->current[] = $classMethod;
                    return true;
                }
            }
        }

        return false;
    }

}
