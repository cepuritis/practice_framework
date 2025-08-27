<?php

namespace Core\Exceptions;

class InvalidHttpMethod extends \Exception
{
    public function __construct(string $method, array $allowedMethods)
    {
        $message = sprintf(
            "Invalid HTTP method %s, Supported methods: %s",
            $method,
            implode(", ", $allowedMethods)
        );
        parent::__construct($message);
    }
}
