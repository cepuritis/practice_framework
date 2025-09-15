<?php

namespace Core\Contracts\Http;

use Core\Contracts\View\ViewInterface;

interface HttpResponseInterface
{
    public function send(): void;

    public function setCode(HttpResponseCode $code): void;

    public function getCode(): HttpResponseCode;

    public function setHeader(string $key, string $value): void;
}
