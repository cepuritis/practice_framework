<?php

namespace Core\Attributes\HttpRequest;

use Attribute;
use Core\Contracts\HttpMethodAttributeInterface;

#[Attribute(Attribute::TARGET_METHOD)]
class GET implements HttpMethodAttributeInterface
{
    /**
     * @return string
     */
    public static function getMethod(): string
    {
        return 'GET';
    }
}
