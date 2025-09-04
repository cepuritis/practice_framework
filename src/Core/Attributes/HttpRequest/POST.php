<?php

namespace Core\Attributes\HttpRequest;

use Attribute;
use Core\Contracts\Http\HttpMethodAttributeInterface;

#[Attribute(Attribute::TARGET_METHOD)]
class POST implements HttpMethodAttributeInterface
{
    /**
     * @return string
     */
    public static function getMethod(): string
    {
        return 'POST';
    }
}
