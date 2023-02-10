<?php

namespace App\Infrastructure\Repository\Tasks;

use App\Application\Exceptions\TaskException;
use App\Infrastructure\Repository\Database;
use Exception;


class DbTasksRepository implements TasksRepositoryInterface
{
    protected $db;

    public function __construct(Database $db){
        $this->db = $db;
    }

    /**
     * Search for ticket in the database
     *
     * @param array $params id (int) and section_id(int)
     * @throws TaskException
     * @return array with task information
     */
    public function getByID($params): array
    {
        $id = array_key_exists("task", $params) ? $params["task"] : null;
        $section_id = $params["section_id"];
        if ($id){
            $sql = "SELECT * FROM tasks WHERE id = :task_id AND section_id = :section_id";
            $this->db->query($sql);
            $this->db->bind(":task_id", $id);
            $this->db->bind(":section_id", $section_id);
            $task = $this->db->single();
            if ($task){
                return $task;
            }
            throw new TaskException("Task does not exist",404);
        }

        throw new TaskException("Something is missing in the request see doc",400);

    }
    /**
     * Search for task in the database
     *
     * @param array $params section_id(id), limit(int) is Optional
     * @return array with tasks information
     */

    public function getAll($params, $queryParam = []): array
    {
        $section_id = $params["section_id"];
        if (array_key_exists("limit", $queryParam)) {
            $sql = "SELECT * FROM tasks WHERE section_id= :section_id LIMIT :limit";
            $this->db->query($sql);
            $this->db->bind(":limit", $queryParam["limit"]);
        } else {
            $sql = "SELECT * FROM tasks WHERE section_id= :section_id";
            $this->db->query($sql);
        }

        $this->db->bind(":section_id", $section_id);

        return $this->db->result_set();

    }

    /**
     * Creates a ticket and save it in the db
     *
     * @param array $params Should have section_id(int); name(string), status_id(int) and $summary are optional
     * @throws TaskException
     * @return bool
     */
    public function create_element($params): bool
    {
        $section_id = $params["section_id"];
        $status_id = array_key_exists("status_id", $params) ? $params["status_id"] : 1;
        $name = array_key_exists("name", $params) ? $params["name"] : "Task";
        $summary = array_key_exists("summary", $params) ? $params["summary"] : "";


        $sql = "INSERT INTO tasks (name, summary, status_id, section_id)
            VALUE (:name,:summary, :status_id, :section_id)";

        $this->db->query($sql);
        $this->db->bind(":section_id", $section_id);
        $this->db->bind(":name", $name);
        $this->db->bind(":summary", $summary);
        $this->db->bind(":status_id", $status_id);

        $this->db->execute();

        return $this->success_verification();

    }

    /**
     * Edit element in the db
     *
     * @param array $params Should have id(int),name(string),status_id(int),$summary(string) and section_id(int)
     * @throws TaskException
     * @return bool
     *
     */
    public function edit_element($params): bool
    {
        $id = array_key_exists("id", $params) ? $params["id"] : null;
        $status_id = array_key_exists("status_id", $params) ? $params["status_id"] : null;
        $name = array_key_exists("name", $params) ? $params["name"] : null;
        $summary = array_key_exists("summary", $params) ? $params["summary"] : null;
        $section_id = array_key_exists("section_id", $params) ? $params["section_id"] : null;

        if ($id && $status_id && $name && $summary && $section_id) {
            $sql = "UPDATE tasks 
                SET name = :name,
                    summary = :summary,
                    status_id = :status_id
                WHERE id = :id AND 
                      section_id = :section_id";

            $this->db->query($sql);
            $this->db->bind(":name", $name);
            $this->db->bind(":summary", $summary);
            $this->db->bind(":status_id", $status_id);
            $this->db->bind(":id", $id);
            $this->db->bind(":section_id", $section_id);

            $this->db->execute();

            return $this->success_verification();
        }
        throw new TaskException("Something is missing in the request see doc",400);
    }

    /**
     * Hard delete from db
     *
     * @param array $params With the id(int) and section id(int)
     * @throws TaskException
     * @return bool
     */
    public function delete_element($params): bool
    {
        $id = array_key_exists("task", $params) ? $params["task"] : null;
        $section_id = array_key_exists("section_id", $params) ? $params["section_id"] : null;
        if ($id && $section_id){
            $sql = "DELETE FROM tasks 
                WHERE id = :id AND section_id = :section_id";

            $this->db->query($sql);
            $this->db->bind(":id", $id);
            $this->db->bind(":section_id", $section_id);
            $this->db->execute();

            return $this->success_verification();
        }

        return false;
    }

    /**
     * Verify if execution was successful
     *
     * @throws TaskException
     * @return bool
     */
    public function success_verification(): bool
    {
        if ($this->db->row_count()== "0"){
            throw new TaskException("Something went wrong", 500);
        }
        return true;
    }
}