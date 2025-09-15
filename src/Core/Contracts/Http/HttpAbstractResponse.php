<?php

namespace Core\Contracts\Http;

use Core\Contracts\View\ViewInterface;
use Core\Exception\TemplateNotSetException;

abstract class HttpAbstractResponse implements HttpResponseInterface
{
    protected HttpResponseCode $responseCode = HttpResponseCode::OK;
    protected array $data = [];

    protected ViewInterface $content;

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
        if (!$this->content) {
            throw new TemplateNotSetException("Response Content Not set !");
        }

        http_response_code($this->responseCode->value);
        echo $this->content->render();
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

    public function setHeader(string $key, string $value): void
    {
        header("{$key}: {$value}");
    }
}