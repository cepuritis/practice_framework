<?php
namespace Controller\Home;

use Core\Attributes\HttpRequest\Route;
use Core\Attributes\HttpRequest\GET;
use Core\HttpRequest;

#[Route('/')]
class Home
{
    #[GET]
    public function getPath(HttpRequest $request)
    {
        echo "This is Home Page";
    }
}