<?php

namespace Core\Config\Helpers;

use Core\Config\Config;

class DatabaseConfig
{
    public string $host;
    public string $port;

    public string $user;

    public string $pass;

    public string $table;

    /**
     * @param Config|array $config
     */
    public function __construct(Config | array $config)
    {
        if ($config instanceof Config) {
            $config = $config->getDatabase();
        }

        $this->host = $config['host'];
        $this->port = $config['port'];
        $this->user = $config['user'];
        $this->pass = $config['pass'];
        $this->table = $config['table'];
    }
}