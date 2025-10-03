<?php
// =============================
// Schema / Documentation block
// =============================
return [
    '_schema' => [
        'session' => [
            'test' => [
                'type' => 'string'
            ],
            'storage' => [
                'type' => 'enum',
                'allowed' => ['files', 'redis', 'memcached'],
                'default' => 'files',
                'description' => 'Where session data is stored'
            ],
            'save_path' => [
                'type' => 'string',
                'default' => '/tmp',
                'description' => 'Filesystem path if using "files" storage'
            ],
            'redis' => [
                'type' => 'object',
                'fields' => [
                    'host' => ['type' => 'string', 'default' => '127.0.0.1'],
                    'port' => ['type' => 'int', 'default' => 6379],
                    'auth' => ['type' => 'string|null'],
                    'database' => ['type' => 'int', 'default' => 0],
                    'prefix' => ['type' => 'string', 'default' => 'sess_'],
                ],
                'description' => 'Redis connection options if storage=redis'
            ],
            'gc_maxlifetime' => [
                'type' => 'int',
                'default' => 1440,
                'description' => 'Session lifetime in seconds'
            ]
        ],

        'database' => [
            'host' => ['type' => 'string', 'default' => '127.0.0.1'],
            'port' => ['type' => 'int', 'default' => 3306],
            'table' => ['type' => 'string'],
            'user' => ['type' => 'string'],
            'pass' => ['type' => 'string'],
        ],

        'csrf_enabled' => [
            'type' => 'bool',
            'default' => true,
            'description' => 'Enable CSRF token checking'
        ],

        'headers' => [
            'type' => 'object',
            'description' => 'Custom headers to send with every response'
        ]
    ]
];
