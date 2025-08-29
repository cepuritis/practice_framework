<?php

namespace Core\Contracts;

use Core\Contracts\HttpRequestInterface;

interface RouterInterface
{
    /**
     * @return bool
     */
    public function match(string $path, HttpRequestMethod $method): bool;

    /**
     * @param HttpRequestInterface $request
     * @return void
     */
    public function dispatch(HttpRequestInterface $request): void;
}
