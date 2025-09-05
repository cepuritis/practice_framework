<?php

namespace Controller\Json;

use Core\Attributes\HttpRequest\GET;
use Core\Attributes\HttpRequest\Route;
use Core\Http\HttpJsonResponse;

#[ROUTE('/json/test1')]
class JsonTestData
{
    #[GET]
    public function get()
    {
        $jsonData = json_encode($this->getTestData());
        $response = new HttpJsonResponse();
        $response->setJsonData($jsonData);
        $response->render();
    }

    private function getTestData()
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
