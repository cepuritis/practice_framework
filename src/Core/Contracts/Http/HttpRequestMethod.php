<?php

namespace Core\Contracts\Http;

use Core\Exceptions\InvalidHttpMethod;
use Core\Helpers\Traits\EnumSupportedValues;

enum HttpRequestMethod: string
{
    use EnumSupportedValues;
    case GET = "GET";
    case POST = "POST";
    case PUT = "PUT";

    /**
     * @param string $method
     * @return HttpRequestMethod
     * @throws InvalidHttpMethod
     */
    public static function fromString(string $method): HttpRequestMethod
    {
        $case = self::tryFrom($method);

        if (!$case) {
            throw new InvalidHttpMethod($method, self::getSupported());
        }

        return $case;
    }
}
