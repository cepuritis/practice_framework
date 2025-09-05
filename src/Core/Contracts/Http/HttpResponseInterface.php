<?php

namespace Core\Contracts\Http;

interface HttpResponseInterface
{
    public function render(): void;

    public function setCode(HttpResponseCode $code): void;

    public function getCode(): HttpResponseCode;

    public function setHeader(string $key, string $value): void;
}
