<?php

namespace Core\Contracts\Utils;

interface ClockInterface
{
    /**
     * @return mixed
     */
    public function now();
}