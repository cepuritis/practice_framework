<?php

namespace Core\Contracts;

use Core\Contracts\HttpRequestInterface;

interface RouterInterface
{
    /**
     * @return bool
     */
    public static function match(): bool;

    /**
     * @param HttpRequestInterface $request
     * @return void
     */
    public function dispatch(HttpRequestInterface $request): void;
}
