<?php

namespace App\Infrastructure\Repository\User;

use App\Application\Exceptions\UserException;
use App\Infrastructure\Repository\Database;


class PdoUserRepository implements UserRepositoryInterface
{
    protected $db;

    public function __construct(Database $db){
        $this->db = $db;
    }

    /**
     * Search for user in the database
     *
     * @param array $params Array containing password and username
     * @return mixed
     *@throws UserException
     */
    public function findUserByUsernamePassword(array $params)
    {
        $password = array_key_exists("password", $params) ? $params["password"] : null;
        $username = array_key_exists("username", $params) ? $params["username"] : null;

        if ($password && $username) {
            $sql = "SELECT * FROM Users WHERE username = :username";
            $this->db->query($sql);
            $this->db->bind(":username", $params["username"]);
            $row = $this->db->single();
            if ($row){
                $hashed_pass = $row->password;

                if (password_verify($password, $hashed_pass)) {
                    return $row;
                }

                throw new UserException("Password or username is incorrect", 400);
            }
            throw new UserException("User not found", 404);
        }
        throw new UserException("Password or username missing", 400);
    }

    /**
     * Register a user to the db
     *
     * @param array $params Array containing password email and username
     * @return bool
     * @throws \Exception
     * @throws UserException
     */
    public function registerUser($params): bool
    {
        $password = array_key_exists("password", $params) ? $params["password"] : null;
        $username = array_key_exists("username", $params) ? $params["username"] : null;
        $email = array_key_exists("email", $params) ? $params["email"] : null;

        if ($password && $username && $email ) {
            $sql = "INSERT INTO Users(username, password, email, created_at)
                                        VALUES(:username, :password, :email, :date)";

            $params["password"] = password_hash($params["password"], PASSWORD_DEFAULT);

            $this->db->query($sql);
            $this->db->bind(":username", $params["username"]);
            $this->db->bind(":password", $params["password"]);
            $this->db->bind(":email", $params["email"]);
            $this->db->bind(":date", date('Y-m-d H:i:s'));
            $this->db->execute();

            return $this->success_verification();
        }
        throw new UserException("Password or username missing", 400);
    }


    /**
     * Verify if execution was successful
     *
     * @throws UserException
     * @return bool
     */
    public function success_verification(): bool
    {
        if ($this->db->row_count()== "0"){
            throw new UserException("Something went wrong", 500);
        }
        return true;
    }
}
