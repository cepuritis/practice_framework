<?php

namespace Core\Contracts;

use Core\Contracts\Http\HttpRequestInterface;

abstract class BaseRouter implements RouterInterface, RouteGeneratorInterface
{
    public const ROUTER_NAME = 'default';
    public const CONTROLLER_DIR = 'src/Controller';
    public const CONTROLLER_PARENT = 'src/';
    public array $routes = [];
    public array $current = [];
    /**
     * @param array $routes
     */
    public function __construct(array $routes)
    {
        $this->routes = array_change_key_case($routes);
    }

    public function dispatch(HttpRequestInterface $request): void
    {
        $this->current[0] = app()->makeTransient($this->current[0]);
    }
}