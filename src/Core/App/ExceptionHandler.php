<?php

namespace Core\App;

use Core\Config\Config;
use Core\Contracts\Application\ExceptionHandlerInterface;
use Core\Contracts\Http\HttpRequestInterface;
use Core\Contracts\View\MessageType;
use Core\Exceptions\Csrf\CsrfException;
use Core\Http\HttpResponse;

class ExceptionHandler implements ExceptionHandlerInterface
{
    private HttpRequestInterface $request;
    private Config $config;

    /**
     * @param HttpRequestInterface $request
     * @param Config $config
     */
    public function __construct(HttpRequestInterface $request, Config $config)
    {

        $this->request = $request;
        $this->config = $config;
    }
    public function handle(\Exception $exception): void
    {

        if ($exception instanceof CsrfException) {
            $this->handleCsrfException($exception);
        } else {
            throw $exception;
        }
    }

    /**
     * @param CsrfException $e
     * @return void
     */
    private function handleCsrfException(CsrfException $e): void
    {
        $response = new HttpResponse();
        $redirectUrl = $this->request->getReferer() ?: $this->request->getHost();
        $response->withMessage(MessageType::ERROR, $e->getMessage())->redirect($redirectUrl);
    }
}
