<?php
namespace Cli;

class GenerateRoutes implements  CommandInterface
{

    public function execute(array $args): void
    {
        echo "Generating Routes" . PHP_EOL;
        var_dump($args);
    }
}