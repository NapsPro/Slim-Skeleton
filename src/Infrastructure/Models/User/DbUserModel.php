<?php

namespace App\Infrastructure\Models\User;

use App\Infrastructure\Models\Database;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpNotFoundException;


class DbUserModel implements UserModelInterface
{
    protected $db;

    public function __construct(Database $db){
        $this->db = $db;
    }
    public function findUserByUsernamePassword($params)
    {

        if ($params["password"] && $params["username"]) {
            $sql = "SELECT * FROM users WHERE username = :username";
            $this->db->query($sql);
            $this->db->bind(":username", $params["username"]);
            $row = $this->db->single();

            $hashed_pass = $row->password;

            if (password_verify($params["password"], $hashed_pass)) {
                return $row;
            }
        }
        return null;
    }

    public function registerUser($params): bool
    {

        if($params["password"] && $params["username"] && $params["email"]){
            $sql = "INSERT INTO users(username, password, email, created_at)
                                        VALUES(:username, :password, :email, :date)";

            $params["password"] = password_hash($params["password"], PASSWORD_DEFAULT);

            $this->db->query($sql);
            $this->db->bind(":username", $params["username"]);
            $this->db->bind(":password", $params["password"]);
            $this->db->bind(":email", $params["email"]);
            $this->db->bind(":date", date('Y-m-d H:i:s'));
            return $this->db->execute();
        }
        return false; // TODO return something that says tha no user or pass was sent
    }
}
