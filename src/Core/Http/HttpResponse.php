<?php

namespace Core\Http;

use Core\Contracts\Http\HttpAbstractResponse;
use Core\Contracts\Http\HttpResponseCode;
use Core\Contracts\Http\HttpResponseInterface;
use Core\Exception\TemplateNotSetException;
use Core\Tags\LinkTag;
use Core\Tags\MetaTag;
use Core\Tags\ScriptTag;
use http\Exception\RuntimeException;

class HttpResponse extends HttpAbstractResponse implements HttpResponseInterface
{
    protected HttpResponseCode $responseCode = HttpResponseCode::OK;
    private ?string $template = null;
    private bool $useBaseTemplate = true;

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
            http_response_code($this->responseCode->value);
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

    public function addLinkTag(LinkTag $link)
    {
        $this->data["linkTags"][] = $link;
    }

}
