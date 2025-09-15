<?php

namespace Core\View;

use Core\Contracts\View\JsonViewInterface;
use Core\Contracts\View\ViewInterface;
use Core\Helpers\JsonHelper;
use Core\Models\DataObject;

class JsonRenderer implements JsonViewInterface
{
    private ?string $json;

    /**
     * @param string|array|null $json
     * @param bool $convertToJson
     * @throws \JsonException
     */
    public function __construct(string|array|null $json = null, bool $convertToJson = false)
    {
        if ($convertToJson === true) {
            $json = json_encode($json);
        }

        JsonHelper::validateJsonAndThrow($json);
        $this->json = $json;
    }


    public function render(DataObject $viewData = null): string
    {
        return $this->getJsonData();
    }

    /**
     * @param string $json
     * @return void
     * @throws \JsonException
     */
    public function setJsonData(string $json): self
    {
        JsonHelper::validateJsonAndThrow($json);
        $this->json = $json;

        return $this;
    }

    public function getJsonData(): string
    {
        return $this->json;
    }

    public function getTemplateName(): string
    {
        'json';
    }
}