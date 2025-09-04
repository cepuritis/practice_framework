<?php

namespace Core\Contracts\Http;

interface HttpMethodAttributeInterface
{
    public static function getMethod(): string;
}