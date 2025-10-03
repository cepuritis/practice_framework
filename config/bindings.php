<?php
app()->bind(\Core\Contracts\Config\ConfigInterface::class, \Core\Config\Config::class);
app()->bind(\Core\Contracts\Http\HttpRequestInterface::class, \Core\Http\HttpRequest::class);
app()->bind(\Core\Contracts\Session\SessionStorageInterface::class, \Core\User\Session::class);
app()->bind(\Core\Contracts\Application\ExceptionHandlerInterface::class, \Core\App\ExceptionHandler::class);
app()->bind(\Core\Security\CsrfTokenManager::class, function ($app) {
    return new Core\Security\CsrfTokenManager(
        app()->make(\Core\Contracts\Session\SessionStorageInterface::class),
        app()->make(\Core\Utils\Time\SystemClock::class),
        CSRF_TOKEN_BITS
    );
});
