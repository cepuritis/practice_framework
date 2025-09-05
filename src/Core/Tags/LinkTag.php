<?php

namespace Core\Tags;

use Core\Contracts\Tags\HtmlTag;

class LinkTag extends HtmlTag
{
    public function __construct(array $attributes = [])
    {
        parent::__construct("link", $attributes);
    }
}