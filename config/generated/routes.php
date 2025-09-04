<?php

return [
  'Core\\Routing\\Routers\\DefaultRouter' => 
  [
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
