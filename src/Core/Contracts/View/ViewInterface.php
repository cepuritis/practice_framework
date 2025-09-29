<?php

namespace Core\Contracts\View;

use Core\Models\Data\DataCollection;

interface ViewInterface
{
    /**
     * @param DataCollection|null $viewData
     * @return string
     */
    public function render(?DataCollection $viewData = null): string;

    public function getTemplateName(): string;
}
