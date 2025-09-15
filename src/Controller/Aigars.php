<?php

namespace Controller;

use Core\Attributes\HttpRequest\Route;
use Core\Attributes\HttpRequest\GET;

#[Route('/aigars')]
class Aigars
{
    #[GET]
    public function get(): void
    {
        echo "This is Aigars URl";
    }
}
