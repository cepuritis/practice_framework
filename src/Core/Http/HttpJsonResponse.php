<?php

namespace Core\Http;

use Core\Contracts\Http\HttpAbstractResponse;
use Core\Contracts\Http\HttpResponseCode;
use Core\Contracts\Http\HttpResponseInterface;
use Core\Contracts\View\JsonViewInterface;
use Core\Contracts\View\ViewInterface;
use Core\Helpers\JsonHelper;
use Core\View\ViewRenderer;

class HttpJsonResponse extends HttpAbstractResponse implements HttpResponseInterface
{
    public function __construct(JsonViewInterface $jsonContent)
    {
        $this->setContent($jsonContent);
    }

    public function send(): void
    {
        $this->setHeader("Content-Type", "application/json");
        parent::send();
    }
}
