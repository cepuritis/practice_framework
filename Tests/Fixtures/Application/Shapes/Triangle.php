<?php

namespace Practice\Tests\Fixtures\Application\Shapes;

class Triangle implements Shape
{

    public function getSides(): int
    {
        return 3;
    }

    public function getName(): string
    {
        return "Triangle";
    }
}