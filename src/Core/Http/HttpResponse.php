<?php

namespace Core\Http;

use Core\Contracts\Http\HttpAbstractResponse;
use Core\Contracts\Http\HttpResponseCode;
use Core\Contracts\Http\HttpResponseInterface;
use Core\Exception\TemplateNotSetException;

class HttpResponse extends HttpAbstractResponse implements HttpResponseInterface
{

}
