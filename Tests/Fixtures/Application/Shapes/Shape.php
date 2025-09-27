<?php

namespace Practice\Tests\Fixtures\Application\Shapes;

interface Shape
{
    public function getSides(): int;

    public function getName(): string;
}
