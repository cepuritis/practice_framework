<?php

namespace Core\Contracts\Http;

use Core\Contracts\View\ViewInterface;
use Core\Exception\TemplateNotSetException;

abstract class HttpAbstractResponse implements HttpResponseInterface
{
    protected HttpResponseCode $responseCode = HttpResponseCode::OK;
    protected array $data = [];

    protected ?ViewInterface $content;

    /**
     * @param ViewInterface $content
     * @return self
     */
    public function setContent(ViewInterface $content): self
    {
        $this->content = $content;
        return $this;
    }

    public function send(): void
    {
        if (!$this->isStatusCodeNotRedirect()) {
            return;
        }

        if ($this->isStatusCodeNotRedirect() && !$this->content) {
            throw new TemplateNotSetException("Response Content Not set !");
        }

        http_response_code($this->responseCode->value);
        echo $this->content->render();
    }

    /**
     * @return bool
     */
    public function isStatusCodeNotRedirect(): bool
    {
        if (in_array($this->responseCode, [
            HttpResponseCode::PERMANENT_REDIRECT,
            HttpResponseCode::TEMPORARY_REDIRECT,
            HttpResponseCode::SEE_OTHER,
            HttpResponseCode::MOVED_PERMANENTLY,
            HttpResponseCode::FOUND
        ])) {
            return false;
        }

        return true;
    }

    /**
     * @param HttpResponseCode $code
     * @return void
     */
    public function setCode(HttpResponseCode $code): void
    {
        $this->responseCode = $code;
    }

    /**
     * @return HttpResponseCode
     */
    public function getCode(): HttpResponseCode
    {
        return $this->responseCode;
    }

    /**
     * @param string $key
     * @param string $value
     * @return void
     */
    public function setHeader(string $key, string $value): void
    {
        header("{$key}: {$value}");
    }
}
