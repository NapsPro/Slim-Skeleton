<?php

namespace App\Infrastructure\Repository;

use PDO;
use PDOException;

class Database
{
    private $host;
    private $user;
    private $pass;
    private $db_name;

    public $dbh;
    private $stmt;
    private $error;

    /**
     * Creates a PDO object
     */
    public function __construct()
    {
        $this->host = $_ENV["DB_HOST"];
        $this->user = $_ENV["DB_USER"];
        $this->pass = $_ENV["DB_PASS"];
        $this->db_name = $_ENV["DB_NAME"];

        $dsn = "mysql:host=" . $this->host . ";dbname=" . $this->db_name;
        $options = [
            PDO::ATTR_PERSISTENT => true,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::MYSQL_ATTR_FOUND_ROWS => true
        ];


        try {
            $this->dbh = new PDO($dsn, $this->user, $this->pass, $options);
        } catch (PDOException $e) {
            $this->error = $e->getMessage();
            echo $this->error; //TODO throw response 500
        }
    }
    /**
     * Prepare statement with query
     * @param string $sql sql query to prepare
     * @return void
     */
    public function query($sql)
    {
        $this->stmt = $this->dbh->prepare($sql);
    }


    /**
     * Bind values to the statement
     *
     * @param string $param
     * @param mixed $value
     * @param mixed $type
     * @return void
     */
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


    /**
     * Execute the prepared statement
     * @return bool
     */
    public function execute() :bool
    {
        return $this->stmt->execute();
    }


    /**
     * Get result set as array of objects
     * @return array
     */
    public function result_set(): array
    {
        $this->execute();
        return $this->stmt->fetchAll(PDO::FETCH_OBJ);
    }

    /**
     * Get single result
     * @return mixed
     */
    public function single()
    {
        if ($this->execute()) {
            return $this->stmt->fetch(PDO::FETCH_OBJ);
        };
        die("xD");
    }

    /**
     * Get row count
     * @return string
     */
    public function row_count(): string
    {
        return $this->stmt->rowCount();
    }
}
