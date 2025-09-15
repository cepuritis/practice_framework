<?php

namespace Core\Contracts\View;

interface JsonViewInterface extends ViewInterface
{
    /**
     * @return string
     */
    public function getJsonData(): string;
}
