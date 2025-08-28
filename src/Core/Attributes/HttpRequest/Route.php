<?php

namespace Core\Attributes\HttpRequest;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
class Route
{
    public function __construct(public string $path)
    {
    }
}
