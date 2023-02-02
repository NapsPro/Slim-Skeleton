<?php

namespace App\Infrastructure\Models\Status;

use App\Infrastructure\Models\Database;

class DbStatus implements StatusModelInterface
{
    protected $db;

    public function __construct(Database $db){
        $this->db = $db;
    }
    public function getByID($id)
    {
        $sql = "SELECT * FROM status WHERE id = :status_id";

        $this->db->query($sql);
        $this->db->bind(":status_id", $id);

        return $this->db->single();
    }

    public function getAll($id, $queryParam = []): array
    {
        $sql = "SELECT * FROM status WHERE user_id = :user_id";

        $this->db->query($sql);
        $this->db->bind(":user_id", $id);

        return $this->db->result_set();
    }

    public function create_element($name, $id): bool
    {
        $sql = "INSERT INTO status (name, user_id)
                VALUE (:name,:user_id)";

        $this->db->query($sql);
        $this->db->bind(":user_id", $id);
        $this->db->bind(":name", $name);

        return $this->db->execute();
    }

    public function edit_element($id, $name): bool
    {
        $sql = "UPDATE sections 
                SET name = :name
                WHERE id = :id";

        $this->db->query($sql);
        $this->db->bind(":name", $name);

        return $this->db->execute();
    }

    public function delete_element($id): bool
    {
        $sql = "DELETE FROM status 
                WHERE id = :id";
        $this->db->query($sql);
        $this->db->bind(":id", $id);

        return $this->db->execute();
    }
}