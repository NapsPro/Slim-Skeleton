<?php

namespace App\Infrastructure\Persistence\Sections;


use App\Infrastructure\Models\Database;
use App\Infrastructure\Models\Sections\SectionModelInterface;

class DbSection implements SectionModelInterface {
    protected $db;

    public function __construct(Database $db){
        $this->db = $db;
    }

    public function getByID($params)
    {
        $id = array_key_exists("id", $params) ? $params["id"] : null;
        if ($id) {
            $sql = "SELECT * FROM sections WHERE id = :section_id";

            $this->db->query($sql);
            $this->db->bind(":section_id", $id);

            return $this->db->single();
        }
        return null;
    }

    public function getAll($params, $queryParam = []): array
    {
        $tab_id = array_key_exists("tab_id", $params) ? $params["tab_id"] : null;
        if ($tab_id){
            $sql = "SELECT * FROM sections WHERE tab_id= :tab_id";

            $this->db->query($sql);
            $this->db->bind(":tab_id", $tab_id);

            return $this->db->result_set();
        }
        return [];
    }

    public function create_element($params): bool
    {
        $tab_id = array_key_exists("tab_id", $params) ? $params["tab_id"] : null;
        $name = array_key_exists("name", $params) ? $params["name"] : null;

        if ($tab_id && $name) {
            $sql = "INSERT INTO sections (name, tab_id)
                VALUE (:name,:tab_id)";

            $this->db->query($sql);
            $this->db->bind(":name", $name);
            $this->db->bind(":tab_id", $tab_id);

            return $this->db->execute();
        }
        return false;
    }

    public function edit_element($params): bool
    {
        $id = array_key_exists("id", $params) ? $params["id"] : null;
        $name = array_key_exists("name", $params) ? $params["name"] : null;
        if ($id && $name) {
            $sql = "UPDATE sections 
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
            $sql = "DELETE FROM sections 
                WHERE id = :id";
            $this->db->query($sql);
            $this->db->bind(":id", $id);

            return $this->db->execute();
        }
        return false;
    }
}