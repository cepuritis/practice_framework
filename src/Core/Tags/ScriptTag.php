<?php

namespace Core\Tags;

use Core\Contracts\Tags\HtmlTag;

class ScriptTag extends HtmlTag
{
    public function __construct(string $src, bool $defer = true, bool $isLocal = false)
    {
        if ($isLocal) {
            $src = PUBLIC_ASSETS_PATH . "/js/{$src}.js";
        }

        $attributes = ['src' => $src];
        if ($defer) {
            $attributes['defer'] = "";
        }
        parent::__construct("script", $attributes);
    }

    public function render(bool $close = false, string $content = ""): void
    {
        $tag =  $this->getTag(true, $content);
        echo $tag;
    }
}
