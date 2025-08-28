<?php

namespace Core\Attributes;

use Attribute;
#[Attribute(Attribute::TARGET_CLASS)]
class Priority
{
    public function __construct(public int $priority)
    {
    }
}
