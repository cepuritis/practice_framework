<?php

namespace Core\Http;

use Core\Contracts\Http\HttpAbstractResponse;
use Core\Contracts\Http\HttpRequestInterface;
use Core\Contracts\Http\HttpResponseCode;
use Core\Contracts\Http\HttpResponseInterface;
use Core\Contracts\View\MessageType;
use Core\Contracts\View\ViewInterface;
use Core\User\Session;

class HttpResponse extends HttpAbstractResponse implements HttpResponseInterface
{
    public function __construct(?ViewInterface $content = null)
    {
        $this->content = $content;
    }

    /**
     * @param string $url
     * @param HttpResponseCode $statusCode
     * @return $this
     */
    public function redirect(string $url, HttpResponseCode $statusCode = HttpResponseCode::SEE_OTHER): self
    {
        $this->setHeader('Location', $url);
        $this->setCode($statusCode);
        return $this;
    }

    /**
     * @param string $key
     * @param array|string $data
     * @param bool $replaceKey
     * @return $this
     */
    public function with(string $key, array | string $data, bool $replaceKey = true): self
    {
        /**
         * @var Session $session
         */
        $session = app()->make(Session::class);

        $session->addFlash($key, $data, $replaceKey);
        return $this;
    }

    /**
     * @param MessageType $type
     * @param string $message
     * @param bool $replaceMessage
     * @return $this
     */
    public function withMessage(MessageType $type, string $message, bool $replaceMessage = false): self
    {
        return $this->with('message', [$type->value => $message], $replaceMessage);
    }

    /**
     * @return $this
     */
    public function withPostData(): self
    {
        /** @var HttpRequestInterface $request */
        $request = app()->make(HttpRequestInterface::class);
        return $this->with('oldPostData', $request->getPostData());
    }
}
