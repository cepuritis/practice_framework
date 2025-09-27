<?php

namespace Core\User;

use Core\Config\Config;

class Session
{
    public const REDIS = 'redis';
    public function __construct(Config $config)
    {
        /** @var string $sessionStorage */
        $sessionStorage = $config->getSession()[Config::SESSION_STORAGE];
        if ($sessionStorage === Session::REDIS) {
            $this->initRedisSessionStorage($config->getSession()[self::REDIS]);
        } else {
            $this->initFileSessionStorage($config->getSession()['save_path'] ?? "");
        }

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * @param array $redis
     * @return void
     */
    private function initRedisSessionStorage(array $redis): void
    {
        if (!extension_loaded('redis')) {
            throw new \RuntimeException(
                'PHP Redis extension is not installed or redis session handler is not available.
                 Please install or enable phpredis extension to use Redis sessions'
            );
        }

        if (empty($redis['host']) || empty($redis['port'])) {
            throw new \RuntimeException("Redis session config requires 'host' and 'port'.");
        }

        $host = $redis['host'];
        $port= $redis['port'];
        $auth = $redis['auth'] ?? null;
        $db = $redis['database'] ?? 0;
        $prefix = $redis['prefix'] ?? 'sess_';

        $redisClient = new \Redis();
        if (!@$redisClient->pconnect($host, $port, 2)) {
            throw new \RuntimeException("Cannot connect to Redis at {$host}:$port");
        }
        if ($auth && !$redisClient->auth($auth)) {
            throw new \RuntimeException("Redis auth failed");
        }

        $redisClient->select($db);
        $redisClient->close();


        $savePath = "tcp://{$host}:{$port}?persistent=1&database={$db}&prefix={$prefix}";

        if ($auth) {
            $savePath .= "&auth={$auth}";
        }

        ini_set('session.save_handler', 'redis');
        ini_set('session.save_path', $savePath);
    }

    private function initFileSessionStorage(string $sessionDir): void
    {
        if ($sessionDir) {
            ini_set('session.save_path', $sessionDir);
        }
    }

    /**
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public function set(string $key, mixed $value): void
    {
        $_SESSION[$key] = $value;
    }

    /**
     * @param string $key
     * @return mixed
     */
    public function get(string $key): mixed
    {
        return $_SESSION[$key];
    }
}
