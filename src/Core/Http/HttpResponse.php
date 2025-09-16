<?php

namespace Core\Http;

use Core\Contracts\Http\HttpAbstractResponse;
use Core\Contracts\Http\HttpResponseInterface;
use Core\Contracts\View\ViewInterface;

class HttpResponse extends HttpAbstractResponse implements HttpResponseInterface
{
    public function __construct(?ViewInterface $content = null)
    {
        $this->content = $content;
    }
}
