<?php
namespace Controller\Home;

use Contracts\Routing\ActionInterface;
class Home implements ActionInterface
{

    public function execute()
    {
        echo "This is homepage";
    }
}