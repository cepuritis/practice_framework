<?php

namespace Core\Http;

use Core\Contracts\Http\HttpAbstractResponse;
use Core\Contracts\Http\HttpResponseCode;
use Core\Contracts\Http\HttpResponseInterface;

class HttpJsonResponse extends HttpAbstractResponse implements HttpResponseInterface
{
    const KEY_JSON = 'json';
    public function render(): void
    {
        $this->setHeader("Content-Type", "application/json");

        $json = $this->data[self::KEY_JSON];

        if (!$json) {
            $this->setCode(HttpResponseCode::INTERNAL_SERVER_ERROR);
            echo json_encode(['error' => 'Invalid JSON']);

        } else {
            echo $json;
        }
    }

    /**
     * @param string $json
     * @return void
     * @throws \JsonException
     */
    public function setJsonData(string $json): void
    {
        if (!$this->validateJson($json)) {
            throw new \JsonException(json_last_error_msg());
        }

        $this->data[self::KEY_JSON] = $json;
    }

    private function validateJson(string $json): bool
    {
        json_decode($json);
        return json_last_error() === JSON_ERROR_NONE;
    }
}