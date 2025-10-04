<?php

return [
    // Session configuration
    'session' => [
        // Storage options: 'files' | 'redis' | 'memcached'
        'storage' => 'redis',

        // For 'files' storage
        'save_path' => '/var/lib/php/sessions',

        // For 'redis' storage
        'redis' => [
            'host' => '127.0.0.1',
            'port' => 6379,
            'auth' => null,
            'database' => 0,
            'prefix' => 'sess_',
        ],

        // Session lifetime in seconds
        'gc_maxlifetime' => 1440,
    ],
    'headers' => [
        "Content-Security-Policy" => "frame-ancestors 'self'"
    ]
];