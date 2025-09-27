<?php

app()->bind(\Core\Contracts\Http\HttpRequestInterface::class, \Core\Http\HttpRequest::class);
