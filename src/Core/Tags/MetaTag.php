<?php

namespace Core\Tags;

use Core\Contracts\Tags\HtmlTag;

class MetaTag extends HtmlTag
{
    public function __construct(array $attributes = [])
    {
        parent::__construct("meta", $attributes);
    }

}
