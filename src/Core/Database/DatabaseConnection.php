<?php

namespace Core\Database;

use Core\Config\Config;
use Core\Config\Helpers\DatabaseConfig;
use Core\Contracts\Config\ConfigInterface;

class DatabaseConnection
{
    private \PDO $connection;

    public function __construct(ConfigInterface $config)
    {
        $dbConfig = new DatabaseConfig($config->getDatabase());

        $dsn = sprintf(
            'mysql:host=%s;port=%s;dbname=%s;',
            $dbConfig->host,
            $dbConfig->port,
            $dbConfig->table
        );

        $this->connection = new \PDO(
            $dsn,
            $dbConfig->user,
            $dbConfig->pass
        );
    }

    /**
     * @param string $sql
     * @param array $params
     * @return false|\PDOStatement
     */
    public function query(string $sql, array $params): false|\PDOStatement
    {
        $stmt = $this->connection->prepare($sql);
        $stmt->execute($params);

        return $stmt;
    }
}