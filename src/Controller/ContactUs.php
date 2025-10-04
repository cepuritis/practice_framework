<?php

namespace Controller;

use Core\Attributes\HttpRequest\GET;
use Core\Attributes\HttpRequest\POST;
use Core\Attributes\HttpRequest\Route;
use Core\Contracts\Session\SessionStorageInterface;
use Core\Contracts\View\MessageType;
use Core\Database\DatabaseConnection;
use Core\Http\HttpRequest;
use Core\Http\HttpResponse;
use Core\View\PageRenderer;

#[Route('/contactus')]
class ContactUs
{
    #[GET]
    public function get(HttpRequest $request, DatabaseConnection $db, SessionStorageInterface $sessionStorage): void
    {
        $view = new PageRenderer('contactus/index');
        $view->setTitle('Contact Us');
        $response = new HttpResponse($view);
        $response->send();
    }

    #[POST]
    public function post(HttpRequest $request, DatabaseConnection $db): void
    {

        //Todo: Escaping the parameters

        $data = $request->getPostData();
        $response = new HttpResponse();

        try {
            $result = $db->query(
                "INSERT INTO contactus (title, text) VALUES (?, ?)",
                [
                    $data['title'],
                    $data['message']
                ]
            );
            $response->withMessage(MessageType::SUCCESS, "The message was submitted successfully!");
        } catch (\Exception $e) {
            $response->withMessage(MessageType::ERROR, "Something went wrong");
        }

        $response->redirect('/contactus');
    }
}
