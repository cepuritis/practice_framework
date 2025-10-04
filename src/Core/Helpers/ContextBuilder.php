<?php

namespace Core\Helpers;

class ContextBuilder
{
    private array $functions;
    private bool $throwWhenMissing;

    public function __construct(array $context = [], bool $throwWhenMissing = true)
    {
        $this->functions = $context;
        $this->throwWhenMissing = $throwWhenMissing;
    }

    public function addFunction($callable, string $name)
    {
        $this->functions[$name] = $callable;
    }

    public function __call(string $name, array $arguments = [])
    {
        if (!isset($this->functions[$name])) {
            if ($this->throwWhenMissing) {
                throw new \RuntimeException("Non existent context function called {$name}");
            }
        } else {
            $this->functions[$name](...$arguments);
        }
    }
}