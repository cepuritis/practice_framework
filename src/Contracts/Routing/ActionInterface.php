<?php
namespace Contracts\Routing;
interface ActionInterface
{
    /**
     * @return void
     */
    public function execute(): void;
}
