<?php

namespace Practice\Tests\Fixtures\Application\Shapes;

class Polygon1
{
    private Shape $shape;

    public function __construct(Shape $shape)
    {
        $this->shape = $shape;
    }

    public function describe(): string
    {
        return sprintf("%s has %s sides", $this->shape->getName(), $this->shape->getSides());
    }
}
