<?php

namespace Core\Contexts\View;

use Core\Models\Data\DataCollection;
use Core\Tags\ScriptTag;
use Core\View\PageRenderer;

/**
 * Provides a new scope for PageRenderer templates
 * to allow access to specific functions only
 */
class PageContext
{
    protected DataCollection $data;
    protected array $metaTags = [];
    protected array $scriptTags = [];
    protected array $linkTags = [];

    private PageRenderer $renderer;

    public function __construct(PageRenderer $renderer, DataCollection $data)
    {
        $this->renderer = $renderer;
        $this->data = $data;
        $this->metaTags = $renderer->getMetaTags();
        $this->scriptTags = $renderer->getScriptTags();
        $this->linkTags = $renderer->getLinkTags();
    }

    public function include(string $template, DataCollection|array $data = []): string
    {
        return $this->renderer->include($template, $data);
    }

    public function addJs(string $src)
    {
        $script = new ScriptTag($src, true, true);
        $this->scriptTags[md5($src)] = $script;
    }

    public function removeJs(string $src)
    {
        $key = md5($src);
        if (isset($this->scriptTags[$key])) {
            unset($this->scriptTags[$key]);
        }
    }
}
