<?php
namespace Core\Contracts;
interface RouteGeneratorInterface
{
    /**
     * @return array
     */
    public static function generate(): array;
}
