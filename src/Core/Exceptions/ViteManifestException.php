<?php

namespace Core\Exceptions;

class ViteManifestException extends \RuntimeException
{
    public function __construct(string $message = "Vite Manifest Error", int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
