<?php

namespace Core\Config\Helpers;

use Core\Config\Config;

class RedisConfig
{
    public ?string $host;
    public ?int $port;
    public ?string $auth = null;
    public ?int $database;
    public ?string $prefix;

    /**
     * @param Config|array $config
     */
    public function __construct(Config | array $config)
    {
        if ($config instanceof  $config) {
            $config = $config->getSession()['redis'];
        }

        $this->host = $config['host'] ?? null;
        $this->port = $config['port'] ?? null;
        $this->auth = $config['auth'] ?? null;
        $this->database = $config['database'] ?? null;
        $this->prefix = $config['prefix'] ?? null;
    }
}