<?php

namespace Controller\About;

use Core\Attributes\HttpRequest\GET;
use Core\Attributes\HttpRequest\Route;
use Core\Http\HttpRequest;
use Core\Http\HttpResponse;
use Core\Models\Data\DataCollection;
use Core\User\Session;
use Core\View\PageRenderer;

#[Route('/about')]
class About
{
    #[GET]
    public function get(HttpRequest $request, Session $userSession): void
    {
        $view = new PageRenderer('About/about');
        $view->setData(new DataCollection(['sessionText' => $userSession->get('test_key')]));
        $view->setTitle("About us");
        $response = new HttpResponse($view);
        $response->send();
    }

}