<?php

namespace Core\Contracts;

interface HttpMethodAttributeInterface
{
    public static function getMethod(): string;
}