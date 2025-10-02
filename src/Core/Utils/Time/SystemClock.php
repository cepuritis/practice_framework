<?php
namespace Core\Utils\Time;

use Core\Contracts\Utils\ClockInterface;

class SystemClock implements ClockInterface
{
    public function now(): int
    {
        return time();
    }
}