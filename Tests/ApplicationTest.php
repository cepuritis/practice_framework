<?php

namespace Tests;

use Core\App\Application;
use PHPUnit\Framework\TestCase;
use Tests\Fixtures\Application\Shapes\Polygon1;
use Tests\Fixtures\Application\Shapes\Polygon2;
use Tests\Fixtures\Application\Shapes\Polygon3;
use Tests\Fixtures\Application\Shapes\Rectangle;
use Tests\Fixtures\Application\Shapes\Shape;
use Tests\Fixtures\Application\Shapes\Triangle;

class ApplicationTest extends TestCase
{
    protected function setUp(): void
    {
        error_reporting(E_ALL);
    }


    public function testSingletonInstance()
    {
        $app1 = Application::getInstance();
        $app2 = Application::getInstance();

        $this->assertSame($app1, $app2);
    }

    public function testRegularBindingWorks()
    {
        app()->bind(Shape::class, Triangle::class);

        $shape = app()->get(Shape::class);

        $this->assertIsString("Triangle", $shape->getName());
    }

    public function testContextualBindingWorks()
    {
        app()->bind(Shape::class, Triangle::class);
        app()->bindWithContext(Shape::class, Triangle::class, Polygon1::class);
        app()->bindWithContext(Shape::class, Rectangle::class, Polygon2::class);

        $polygon1 = app()->get(Polygon1::class);
        $polygon2 = app()->get(Polygon2::class);
        $polygon3 = app()->get(Polygon3::class);

        $triangle = app()->make(Triangle::class);

        $this->assertStringContainsString("Triangle", $polygon1->describe());
        $this->assertStringContainsString("Rectangle", $polygon2->describe());
        $this->assertStringContainsString("Triangle", $polygon3->describe());
        $this->assertNotSame($polygon1, $polygon3);
        $this->assertSame($triangle, $polygon3->getShape());
    }

}
