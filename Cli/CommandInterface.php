<?php
namespace Cli;
interface CommandInterface
{
    public function execute(array $args): void;
}