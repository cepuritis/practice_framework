<?php

use Core\Contracts\Session\SessionStorageInterface;

app()->bind(\Core\Contracts\Http\HttpRequestInterface::class, \Core\Http\HttpRequest::class);
app()->bind(SessionStorageInterface::class, \Core\User\Session::class);
app()->bind(\Core\Security\CsrfTokenManager::class, function ($app) {
    return new Core\Security\CsrfTokenManager(
        app()->make(SessionStorageInterface::class),
        app()->make(\Core\Utils\Time\SystemClock::class),
        CSRF_TOKEN_BITS
    );
});
