<?php

namespace App\Infrastructure\Models\Tabs;

use App\Infrastructure\Models\Database;

class DbTab implements TabsModelInterface
{
    protected $db;

    public function __construct(Database $db){
        $this->db = $db;
    }

    public function getByID($params)
    {
        $id = array_key_exists("id", $params) ? $params["id"] : null;
        if ($id) {
            $sql = "SELECT * FROM tabs WHERE id = :tab_id";

            $this->db->query($sql);
            $this->db->bind(":tab_id", $id);

            return $this->db->single();
        }
        return null;
    }

    public function getAll($params, $queryParam = []): array
    {
        $slug = array_key_exists("ticket_slug", $params) ? $params["ticket_slug"] : null;
        if ($slug){
            $id = explode("-",$slug)[1];
            $sql = "SELECT * FROM tabs WHERE tabs.ticket_id = :ticket_id";
            $this->db->query($sql);
            $this->db->bind(":ticket_id", $id);
            return $this->db->result_set();
            }

        return [];

    }

    public function create_element($params): bool
    {
        $ticket_id = array_key_exists("ticket_id", $params) ? $params["ticket_id"] : null;
        $name = array_key_exists("name", $params) ? $params["name"] : null;

        if ($ticket_id && $name) {
            $sql = "INSERT INTO tabs (name, ticket_id) 
                    VALUE (:name,:ticket_id)";

            $this->db->query($sql);
            $this->db->bind(":ticket_id", $ticket_id);
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
            $sql = "UPDATE tabs 
                SET name = :name
                WHERE id = :id";

            $this->db->query($sql);
            $this->db->bind(":id", $id);
            $this->db->bind(":name", $name);
            return $this->db->execute();
        }
        return false;
    }

    public function delete_element($params): bool
    {
        $slug = array_key_exists("ticket_slug", $params) ? $params["id"] : null;
        if ($slug){
            $id = explode("-",$slug)[1];
            $sql = "DELETE FROM tabs 
                WHERE id = :id";
            $this->db->query($sql);
            $this->db->bind(":id", $id);
            return $this->db->execute();
        }

        return false;
    }
}
