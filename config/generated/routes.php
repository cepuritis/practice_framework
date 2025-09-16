<?php

return [
  'Core\\Routing\\Routers\\DefaultRouter' => 
  [
    '/about' => 
    [
      'class' => 'Controller\\About\\About',
      'methods' => 
      [
        'GET' => 'get',
      ],
    ],
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
