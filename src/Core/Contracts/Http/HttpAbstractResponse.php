<?php

namespace Core\Contracts\Http;

abstract class HttpAbstractResponse implements HttpResponseInterface
{
    protected HttpResponseCode $responseCode;
    protected array $data = [];
    /**
     * @param string $title
     * @return $this
     */
    public function setTitle(string $title): self
    {
        $this->data["title"] = $title;
        return $this;
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