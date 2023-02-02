<?php

namespace App\Infrastructure\Models\Tickets;


use App\Infrastructure\Models\Database;

class DbTicketModel implements TicketModelInterface
{
    protected $db;

    public function __construct(Database $db){
        $this->db = $db;
    }

    public function getAll($params, $queryParam = []): array
    {
        $user_id = array_key_exists("user_id", $params) ? $params["user_id"] : null;
        if ($user_id){
            $sql = "SELECT * FROM tickets WHERE user_id = :user_id";
            $this->db->query($sql);
            $this->db->bind(":user_id", $user_id);

            return $this->db->result_set();
        }

        //throw new TicketException();
        return [];
    }

    public function getByID($params)
    {
        $id = array_key_exists("ticket_slug", $params) ? $params["ticket_slug"] : null;
        if ($id){
            $sql = "SELECT * FROM tickets WHERE slug = :id";
            $this->db->query($sql);
            $this->db->bind(":id", $id);

            return $this->db->single();
        }
        return null;
    }

    public function create_element($params): bool
    {
        $id = array_key_exists("id", $params) ? $params["id"] : null;
        $status_id = array_key_exists("status_id", $params) ? $params["status_id"] : 1;
        $name = array_key_exists("name", $params) ? $params["name"] : null;

        if ($id && $status_id && $name){
            $sql = "INSERT INTO tickets (name, status_id, user_id, slug) 
                VALUE (:name,:status_id,:user_id,:slug)";

            $slug = "T-".$name;

            $this->db->query($sql);
            $this->db->bind(":user_id", $id);
            $this->db->bind(":name", $name);
            $this->db->bind(":status_id", $status_id);
            $this->db->bind(":slug", $slug);

            return $this->db->execute();
        }

        return false;
    }

    public function edit_element($params): bool
    {
        $id = array_key_exists("id", $params) ? $params["id"] : null;
        $status_id = array_key_exists("status_id", $params) ? $params["status_id"] : 1;
        $name = array_key_exists("name", $params) ? $params["name"] : null;
        if ($id && $status_id && $name) {
            $sql = "UPDATE tickets 
                SET name = :name,
                user_id = :user_id,
                status_id = :status_id,
                slug = :slug
                WHERE id = :id";

            $slug = "T-".$name;
            $this->db->query($sql);
            $this->db->bind(":user_id", $id);
            $this->db->bind(":name", $name);
            $this->db->bind(":status_id", $status_id);
            $this->db->bind(":slug", $slug);

            return $this->db->execute();
        }
        return false;
    }

    public function delete_element($params): bool
    {

        $slug = array_key_exists("ticket_slug", $params) ? $params["id"] : null;
        if ($slug){
            $sql = "DELETE FROM tickets 
                WHERE slug = :slug";
            $this->db->query($sql);
            $this->db->bind(":slug", $slug);

            return $this->db->execute();
        }
        return false;
    }
}
