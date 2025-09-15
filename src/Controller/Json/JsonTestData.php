<?php

namespace Controller\Json;

use Core\Attributes\HttpRequest\GET;
use Core\Attributes\HttpRequest\Route;
use Core\Http\HttpJsonResponse;
use Core\View\JsonRenderer;
use Core\View\ViewRenderer;

#[ROUTE('/json/test1')]
class JsonTestData
{
    /**
     * @throws \JsonException
     */
    #[GET]
    public function get(): void
    {
        $jsonView = (new JsonRenderer($this->getTestData(), true));
        $response = new HttpJsonResponse($jsonView);
        $response->send();
    }

    private function getTestData(): array
    {
        return [
            'user' => [
                'id' => 123,
                'name' => 'John Doe',
                'email' => 'john.doe@example.com',
                'roles' => ['admin', 'editor'],
                'active' => true,
                'profile' => [
                    'age' => 30,
                    'country' => 'USA',
                    'languages' => ['English', 'Spanish']
                ]
            ],
            'posts' => [
                [
                    'id' => 1,
                    'title' => 'Hello World',
                    'content' => 'This is a test post.',
                    'published' => true,
                    'tags' => ['intro', 'welcome']
                ],
                [
                    'id' => 2,
                    'title' => 'Another Post',
                    'content' => 'More content here.',
                    'published' => false,
                    'tags' => []
                ]
            ],
            'meta' => [
                'page' => 1,
                'perPage' => 10,
                'total' => 2
            ]
        ];
    }
}
