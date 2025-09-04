<?php

namespace Core;

use Core\Contracts\Http\HttpResponseCode;
use Core\Contracts\Http\HttpResponseInterface;
use Core\Exception\TemplateNotSetException;
use Core\Tags\MetaTag;
use Core\Tags\ScriptTag;
use http\Exception\RuntimeException;

class HttpResponse implements HttpResponseInterface
{
    private HttpResponseCode $responseCode = HttpResponseCode::OK;
    private ?string $template = null;
    private bool $useBaseTemplate = true;
    private array $data = [];

    /**
     * @param string $template
     * @return void
     */
    public function setTemplate(string $template, bool $useBaseTemplate = true): void
    {
        $this->template = $template;
        $this->useBaseTemplate = $useBaseTemplate;
    }

    /**
     * @return void
     */
    public function render(): void
    {
        if (!$this->template) {
            throw new TemplateNotSetException();
        }

        extract($this->data);
        if ($this->useBaseTemplate) {
            $templateFile = VIEW_PATH . "/{$this->template}";
            $baseTemplate = VIEW_PATH . "/Base/index.phtml";
            if (!file_exists($templateFile) || $templateFile === $baseTemplate) {
                throw new RuntimeException("Invalid template file specified");
            }
            $template = null;
            ob_start();
            include $templateFile;
            $template = ob_get_clean();
            ob_start();
            include $baseTemplate;
            echo ob_get_clean();
        }
    }

    public function addMetaTag(MetaTag $tag): self
    {
        $this->data["metaTags"][] = $tag;
        return $this;
    }

    /**
     * @param ScriptTag $script
     * @return void
     */
    public function addExternalScript(ScriptTag $script)
    {
        $this->data["scripts"][] = $script;
    }

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
}
