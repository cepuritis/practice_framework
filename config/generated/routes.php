<?php

return [
  'Core\\Routing\\Routers\\DefaultRouter' => 
  [
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
