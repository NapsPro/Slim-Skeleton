<?php

namespace App\Infrastructure\Models\Tasks;

use App\Infrastructure\Models\Database;
use App\Infrastructure\Models\Tasks\TasksModelInterface;


class DbTasksModel implements TasksModelInterface
{
    protected $db;

    public function __construct(Database $db){
        $this->db = $db;
    }

    public function getByID($params)
    {
        $id = array_key_exists("tab", $params) ? $params["tab"] : null;
        if ($id){
            $sql = "SELECT * FROM tasks WHERE id = :task_id";
            $this->db->query($sql);
            $this->db->bind(":task_id", $id);

            return $this->db->single();
        }

        //throw new TicketException();
        return null;


    }

    public function getAll($params, $queryParam = []): array
    {
        $id = array_key_exists("section_id", $params) ? $params["section_id"] : null;
        if ($id) {
            if (array_key_exists("limit", $queryParam)) {
                $sql = "SELECT * FROM tasks WHERE section_id= :section_id LIMIT :limit";
                $this->db->bind(":limit", $queryParam["limit"]);
            } else {
                $sql = "SELECT * FROM tasks WHERE section_id= :section_id";
                $this->db->query($sql);
            }

            $this->db->bind(":section_id", $id);

            return $this->db->result_set();
        }
        return [];
    }

    public function create_element($params): bool
    {
        $section_id = array_key_exists("section_id", $params) ? $params["section_id"] : null;
        $status_id = array_key_exists("status_id", $params) ? $params["status_id"] : 1;
        $name = array_key_exists("name", $params) ? $params["name"] : "Task";
        $summary = array_key_exists("summary", $params) ? $params["summary"] : "";

        if ($section_id){
            $sql = "INSERT INTO tasks (name, summary, status_id, section_id)
                VALUE (:name,:summary, :status_id, :section_id)";

            $this->db->query($sql);
            $this->db->bind(":section_id", $section_id);
            $this->db->bind(":name", $name);
            $this->db->bind(":summary", $summary);
            $this->db->bind(":status_id", $status_id);

            return $this->db->execute();
        }

        return false;
    }

    public function edit_element($params): bool
    {
        $id = array_key_exists("id", $params) ? $params["id"] : null;
        $status_id = array_key_exists("status_id", $params) ? $params["status_id"] : null;
        $name = array_key_exists("name", $params) ? $params["name"] : null;
        $summary = array_key_exists("summary", $params) ? $params["summary"] : null;

        if ($id && $status_id && $name && $summary) {
            $sql = "UPDATE tasks 
                SET name = :name,
                    summary = :summary,
                    status_id = :status_id
                WHERE id = :id";

            $this->db->query($sql);
            $this->db->bind(":name", $name);
            $this->db->bind(":summary", $summary);
            $this->db->bind(":status_id", $status_id);
            $this->db->bind(":id", $id);

            return $this->db->execute();
        }
        return false;
    }

    public function delete_element($params): bool
    {
        $id = array_key_exists("task", $params) ? $params["task"] : null;
        if ($id){
            $sql = "DELETE FROM tasks 
                WHERE id = :id";

            $this->db->query($sql);
            $this->db->bind(":id", $id);
            return $this->db->execute();
        }

        return false;
    }
}