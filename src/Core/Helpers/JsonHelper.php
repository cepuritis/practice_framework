<?php

namespace Core\Helpers;

class JsonHelper
{
    public static function validateJson(string $json): bool
    {
        json_decode($json);
        return json_last_error() === JSON_ERROR_NONE;
    }

    /**
     * @param string $json
     * @return bool
     * @throws \JsonException
     */
    public static function validateJsonAndThrow(string $json): bool
    {
        if (!self::validateJson($json)) {
            throw new \JsonException(json_last_error_msg());
        }

        return true;
    }
}