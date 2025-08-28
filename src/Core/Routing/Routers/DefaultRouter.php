<?php

namespace Core\Routing\Routers;

use Core\Contracts\HttpRequestInterface;
use Core\Contracts\RouterInterface;

class DefaultRouter implements RouterInterface
{
    /**
     * @param HttpRequestInterface $request
     * @return void
     */
    public function dispatch(HttpRequestInterface $request): void
    {

    }

    /**
     * @return bool
     */
    public static function match(): bool
    {
        // TODO: Implement match() method.
    }
}