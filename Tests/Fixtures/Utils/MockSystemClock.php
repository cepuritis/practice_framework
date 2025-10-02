<?php

namespace Tests\Fixtures\Utils;

use Core\Contracts\Utils\ClockInterface;

class MockSystemClock implements ClockInterface
{
    private int $addedTime = 0;

    /**
     * @return int
     */
    public function now(): int
    {
        return time() + $this->addedTime;
    }

    /**
     * @param int $seconds
     * @return void
     */
    public function advance(int $seconds): void
    {
        $this->addedTime += $seconds;
    }

    /**
     * @param int $seconds
     * @return void
     */
    public function rewind(int $seconds): void
    {
        $this->addedTime -= $seconds;
    }

    /**
     * @param int $seconds
     * @return void
     */
    public function setAddedTime(int $seconds): void
    {
        $this->addedTime = $seconds;
    }
}
