<?php

return [
  'Core\\Routing\\Routers\\DefaultRouter' => 
  [
    '/json/test1' => 
    [
      'class' => 'Controller\\Json\\JsonTestData',
      'methods' => 
      [
        'GET' => 'get',
      ],
    ],
    '/aigars' => 
    [
      'class' => 'Controller\\Aigars',
      'methods' => 
      [
        'GET' => 'get',
      ],
    ],
    '/' => 
    [
      'class' => 'Controller\\Home\\Home',
      'methods' => 
      [
        'GET' => 'getPath',
      ],
    ],
  ],
];
