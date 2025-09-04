<?php

namespace Core\Exception;

class TemplateNotSetException extends \LogicException
{
    public function __construct(string $message = "No template has been set for the response.", int $code = 0, ?\Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}