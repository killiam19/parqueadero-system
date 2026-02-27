<?php

namespace Framework;

use PDO;

class Database
{
    private $connection;
    private $statement; // sentencia

    public function __construct()
    {
        $dsn = sprintf(
            'mysql:host=%s;dbname=%s;charset=%s',
            $_ENV['MYSQL_HOST'] ?? 'db',
            $_ENV['MYSQL_DATABASE'] ?? 'parqueadero_system',
            'utf8mb4'
        );

        // var_dump($dsn); die();

        $this->connection = new PDO($dsn, config('username'), config('password'), config('options', []));
    }

    public function query($sql, $params = [])
    {
        $this->statement = $this->connection->prepare($sql);
        $this->statement->execute($params);

        return $this;
    }

    public function get()
    {
        return $this->statement->fetchAll();
    }

    public function first()
    {
        return $this->statement->fetch();
    }

    public function firstOrFail()
    {
        $result = $this->first();

        if (!$result) {
            exit('404 Not Found');
        }

        return $result;
    }

    public function beginTransaction()
    {
        return $this->connection->beginTransaction();
    }

    public function commit()
    {
        return $this->connection->commit();
    }

    public function rollBack()
    {
        return $this->connection->rollBack();
    }

    public function inTransaction()
    {
        return $this->connection->inTransaction();
    }
}
