<?php

namespace Core\Routing;

use Core\Contracts\HttpRequestInterface;

class FrontController
{
    private HttpRequestInterface $request;
    public function __construct(HttpRequestInterface $request)
    {
        $this->request = $request;
    }
    public function dispatch()
    {
        foreach ($this->getAllRouters() as $router) {
            if ($router->match()) {
                $router->dispatch();
            }
        }
    }

    /**
     * @return array<string>
     */
    private function getAllRouters(): array
    {

    }
}
