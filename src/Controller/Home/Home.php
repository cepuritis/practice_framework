<?php
namespace Controller\Home;

use Core\Attributes\HttpRequest\Route;
use Core\Attributes\HttpRequest\GET;

#[Route('/')]
class Home
{
    #[GET]
    public function getPath()
    {
        echo "This is Home Page";
    }
}