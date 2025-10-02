<?php

namespace Tests\Fixtures\Application\Shapes;

class Rectangle implements Shape
{

    public function getSides(): int
    {
        return 4;
    }

    public function getName(): string
    {
        return "Rectangle";
    }
}