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

    public function getAll($id, $queryParam = []): array
    {

        $sql = "SELECT * FROM sections WHERE tab_id= :tab_id";

        $this->db->query($sql);
        $this->db->bind(":tab_id", $id);

        return $this->db->result_set();
    }

    public function create_element($name, $id, $tab_id = 1): bool
    {
        $sql = "INSERT INTO sections (id, name, tab_id)
                VALUE (:name,:id, :tab_id)";

        $this->db->query($sql);
        $this->db->bind(":id", $id);
        $this->db->bind(":name", $name);
        $this->db->bind(":tab_id", $tab_id);

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
        $sql = "DELETE FROM sections 
                WHERE id = :id";
        $this->db->query($sql);
        $this->db->bind(":id", $id);

        return $this->db->execute();
    }
}