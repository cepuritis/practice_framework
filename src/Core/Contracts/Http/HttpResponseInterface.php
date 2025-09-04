<?php

namespace Core\Contracts\Http;

interface HttpResponseInterface
{
    public function setTemplate(string $template, bool $useBaseTemplate = true): void;

    public function render(): void;

    public function setCode(HttpResponseCode $code): void;

    public function getCode(): HttpResponseCode;
}