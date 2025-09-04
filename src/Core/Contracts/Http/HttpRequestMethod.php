<?php

namespace Core\Contracts\Http;

use Core\Exceptions\InvalidHttpMethod;

enum HttpRequestMethod: string
{
    case GET = "GET";
    case POST = "POST";
    case PUT = "PUT";

    /**
     * @return array|string[]
     */
    public static function getSupported()
    {
        return array_map(fn ($case) => $case->value, self::cases());
    }

    /**
     * @param string $method
     * @return HttpRequestMethod
     * @throws InvalidHttpMethod
     */
    public static function fromString(string $method)
    {
        $case = self::tryFrom($method);

        if (!$case) {
            throw new InvalidHttpMethod($method, self::getSupported());
        }

        return $case;
    }
}
