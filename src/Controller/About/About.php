<?php

namespace Controller\About;
use Core\Attributes\HttpRequest\GET;
use Core\Attributes\HttpRequest\Route;
use Core\Http\HttpRequest;
use Core\Http\HttpResponse;
use Core\View\PageRenderer;

#[Route('/about')]
class About
{
    #[GET]
    public function get(HttpRequest $request): void
    {
        $view = new PageRenderer('About/about');
        $view->setTitle("About us");
        $response = new HttpResponse($view);
        $response->send();
    }

}