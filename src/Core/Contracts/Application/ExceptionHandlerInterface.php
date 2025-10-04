<?php

namespace Core\Contracts\Application;

interface ExceptionHandlerInterface
{
    public function handle(\Exception $exception);
}