<?php

namespace Core\User;

use Core\Config\Config;
use Core\Config\Helpers\RedisConfig;
use Core\Contracts\Config\ConfigInterface;
use Core\Contracts\Session\SessionStorageInterface;

class Session implements SessionStorageInterface
{
    public const FLASH_DATA_KEY = 'FLASH_DATA';
    public const REDIS = 'redis';

    private ?array $flashData = null;
    public function __construct(ConfigInterface $config)
    {
        if (app()->hasInstanceOf(static::class)) {
            throw new \RuntimeException("Attempting to create multiple Session instances - not allowed");
        }


        /** @var string $sessionStorage */
        $sessionStorage = $config->getSession()[Config::SESSION_STORAGE];
        if ($sessionStorage === Session::REDIS) {
            $this->initRedisSessionStorage(new RedisConfig($config));
        } else {
            $this->initFileSessionStorage($config->getSession()['save_path'] ?? "");
        }

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * @param RedisConfig $redis
     * @return void
     */
    private function initRedisSessionStorage(RedisConfig $redis): void
    {
        if (!extension_loaded('redis')) {
            throw new \RuntimeException(
                'PHP Redis extension is not installed or redis session handler is not available.
                 Please install or enable phpredis extension to use Redis sessions'
            );
        }

        if (is_null($redis->host) || is_null($redis->port)) {
            throw new \RuntimeException("Redis session config requires 'host' and 'port'.");
        }

        $host = $redis->host;
        $port= $redis->port;
        $auth = $redis->auth ?? null;
        $db = $redis->database ?? 0;
        $prefix = $redis->prefix ?? 'sess_';

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

    /**
     * @param string $sessionDir
     * @return void
     */
    private function initFileSessionStorage(string $sessionDir): void
    {
        if ($sessionDir) {
            ini_set('session.save_path', $sessionDir);
        }
    }

    /**
     * @param string $key
     * @param mixed $value
     * @return mixed
     */
    public function set(string $key, mixed $value): mixed
    {
        return $_SESSION[$key] = $value;
    }

    /**
     * @param string $key
     * @return mixed
     */
    public function get(string $key): mixed
    {
        return $_SESSION[$key] ?? null;
    }

    /**
     * @param string $key
     * @return mixed
     */
    public function remove(string $key): mixed
    {
        if (isset($_SESSION[$key])) {
            $value = $_SESSION[$key];
            unset($_SESSION[$key]);
            return $value;
        }

        return null;
    }

    /**
     * @param $key
     * @param array|string $data
     * @param bool $replace
     * @return void
     */
    public function addFlash($key, array | string $data, bool $replace = true): void
    {
        if (!$replace && isset($_SESSION[self::FLASH_DATA_KEY][$key])) {
            $existing =  $_SESSION[self::FLASH_DATA_KEY][$key];
            $_SESSION[self::FLASH_DATA_KEY][$key] = is_array($existing) ? [...$existing, $data] : [$existing, $data];
        } else {
            $_SESSION[self::FLASH_DATA_KEY][$key] = $data;
        }
    }

    /**
     * @return array
     */
    public function getFlash(): array
    {
        if (is_null($this->flashData)) {
            $this->flashData = $_SESSION[self::FLASH_DATA_KEY] ?? [];
            unset($_SESSION[self::FLASH_DATA_KEY]);
        }

        return $this->flashData;
    }
}
