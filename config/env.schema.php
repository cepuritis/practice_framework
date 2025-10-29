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
                'default' => 'redis',
                'description' => 'Where session data is stored'
            ],
            'save_path' => [
                'type' => 'string',
                'default' => '/var/lib/php/sessions',
                'description' => 'Filesystem path if using "files" storage'
            ],
            'redis' => [
                'type' => 'object',
                'fields' => [
                    'host' => ['type' => 'string', 'default' => 'redis'],
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
            'type' => 'object',
            'fields' => [
                'host' => [
                    'type' => 'string',
                    'default' => 'database'
                ],
                'port' => [
                    'type' => 'int',
                    'default' => 3306
                ],
                'table' => [
                    'type' => 'string',
                    'default' => 'practice'
                ],
                'user' => [
                    'type' => 'string',
                    'default' => 'root'
                ],
                'pass' => [
                    'type' => 'string',
                    'default' => 'option123'
                ],
            ],
            'description' => 'Database Configurations'
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
