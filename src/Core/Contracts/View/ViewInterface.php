<?php

namespace Core\Contracts\View;

use Core\Models\DataObject;

interface ViewInterface
{
    /**
     * @param DataObject|null $viewData
     * @return string
     */
    public function render(?DataObject $viewData = null): string;

    public function getTemplateName(): string;
}
