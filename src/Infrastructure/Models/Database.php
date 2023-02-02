<?php

namespace App\Infrastructure\Models;

use PDO;
use PDOException;

class Database
{
    private $host = "10.42.0.2";
    private $user = "sandbox";
    private $pass = "sandbox";
    private $db_name = "sandbox";

    private $dbh;
    private $stmt;
    private $error;

    public function __construct()
    {
        //set DSN
        $dsn = "mysql:host=" . $this->host . ";dbname=" . $this->db_name;
        $options = [
            PDO::ATTR_PERSISTENT => true,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ];

        //create PDO instance

        try {
            $this->dbh = new PDO($dsn, $this->user, $this->pass, $options);
        } catch (PDOException $e) {
            $this->error = $e->getMessage();
            echo $this->error; //TODO throw response 500
        }
    }

    //prepare statement with query
    public function query($sql)
    {
        $this->stmt = $this->dbh->prepare($sql);
    }

    //bind values
    public function bind($param, $value, $type = null)
    {
        if (is_null($type)) {
            switch (true) {
                case is_int($value):
                    $type = PDO::PARAM_INT;
                    break;
                case is_bool($value):
                    $type = PDO::PARAM_BOOL;
                    break;
                case is_null($value):
                    $type = PDO::PARAM_NULL;
                    break;
                default:
                    $type = PDO::PARAM_STR;
            }
        }
        $this->stmt->bindValue($param, $value, $type);
    }

    //execute the prepared statement
    public function execute() :bool
    {
        return $this->stmt->execute();
    }

    //get result set as array of objects
    public function result_set(): array
    {
        $this->execute();
        return $this->stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    //get single record as objected
    public function single()
    {
        if ($this->execute()) {
            return $this->stmt->fetch(PDO::FETCH_ASSOC);
        };
        die("xD");
    }

    //get row count
    public function row_count()
    {
        return $this->stmt->rowCount();
    }
}
