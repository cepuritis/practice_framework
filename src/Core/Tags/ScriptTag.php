<?php

namespace Core\Tags;

use Core\Contracts\Tags\HtmlTag;

class ScriptTag extends HtmlTag
{
    public function __construct(string $src, bool $defer = true)
    {
        $attributes = ['src' => $src];
        if ($defer) {
            $attributes['defer'] = "";
        }
        parent::__construct("script", $attributes);
    }

    public function render(bool $close = false, string $content = ""): void
    {
        parent::render(true,);
    }
}