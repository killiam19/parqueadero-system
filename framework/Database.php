<?php

namespace Framework;

use PDO;

class Database
{
    private $connection;
    private $statement; // sentencia

    public function __construct()
    {
        // $dsn = 'mysql:host=127.0.0.1;dbname=web-php;charset=utf8mb4';
        $dsn = sprintf(
            'mysql:host=%s;dbname=%s;charset=%s',
            config('host', 'localhost'),
            config('dbname', 'parqueadero-system'),
            config('charset')
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
}