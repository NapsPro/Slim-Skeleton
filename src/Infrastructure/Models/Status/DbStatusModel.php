<?php

namespace App\Infrastructure\Models\Status;

use App\Infrastructure\Models\Database;

class DbStatusModel implements StatusModelInterface
{
    protected $db;

    public function __construct(Database $db){
        $this->db = $db;
    }
    public function getByID($params)
    {
        $status_id = array_key_exists("id", $params) ? $params["status_id"] : null;
        if ($status_id) {
            $sql = "SELECT * FROM status WHERE id = :status_id";

            $this->db->query($sql);
            $this->db->bind(":status_id", $status_id);

            return $this->db->single();
        }
        return null;
    }

    public function getAll($params, $queryParam = []): array
    {
        $user_id = array_key_exists("user_id", $params) ? $params["user_id"] : null;
        if ($user_id) {
            $sql = "SELECT * FROM status WHERE user_id = :user_id";

            $this->db->query($sql);
            $this->db->bind(":user_id", $user_id);

            return $this->db->result_set();
        }
        return [];
    }

    public function create_element($params): bool
    {

        $user_id = array_key_exists("user_id", $params) ? $params["user_id"] : null;
        $name = array_key_exists("name", $params) ? $params["name"] : null;

        if ($user_id && $name) {
            $sql = "INSERT INTO status (name, user_id)
                VALUE (:name,:user_id)";

            $this->db->query($sql);
            $this->db->bind(":user_id", $user_id);
            $this->db->bind(":name", $name);

            return $this->db->execute();
        }
        return false;
    }

    public function edit_element($params): bool
    {

        $id = array_key_exists("id", $params) ? $params["id"] : null;
        $name = array_key_exists("name", $params) ? $params["name"] : null;
        if ($id && $name) {
            $sql = "UPDATE status 
                SET name = :name
                WHERE id = :id";

            $this->db->query($sql);
            $this->db->bind(":name", $name);

            return $this->db->execute();
        }
        return false;
    }

    public function delete_element($params): bool
    {
        $id = array_key_exists("id", $params) ? $params["id"] : null;
        if ($id) {
            $sql = "DELETE FROM status 
                WHERE id = :id";
            $this->db->query($sql);
            $this->db->bind(":id", $id);

            return $this->db->execute();
        }
        return false;
    }
}