<?php

namespace Framework;

use PDO;

class Database
{
    private $connection;
    private $statement; //Sentencia

    public function __construct()
    {
        $dsn ='mysql:host=127.0.0.1;dbname=parqueadero_system;charset=utf8mb4';

        $this->connection = new PDO($dsn,'root','1213123Shape');
    }

    public function query($sql, $params =[])
    {
        $this->statement = $this->connection->prepare($sql);
        $this->statement->execute($params);
        return $this;
    }

    public function get()
    {
        return $this->statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function firstOrFail()
    {
        $result = $this->statement->fetch(PDO::FETCH_ASSOC);

        if(!$result){
            exit('404 Not Found');
        }
        return $result;
    }
}