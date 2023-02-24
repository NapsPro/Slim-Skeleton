<?php

namespace App\Infrastructure\Repository\Tasks;

use App\Application\Exceptions\TaskException;
use App\Infrastructure\Repository\Database;
use Exception;


class PdoTasksRepository implements TasksRepositoryInterface
{
    protected $db;

    public function __construct(Database $db){
        $this->db = $db;
    }

    /**
     * Search for ticket in the database
     *
     * @param array $id id (int) and section_id(int)
     * @throws TaskException
     * @return mixed
     */
    public function getByID($id)
    {
        $task_id = $id["id"];
        $section_id = $id["section_id"];
        $sql = "SELECT * FROM Tasks WHERE id = :task_id AND section_id = :section_id";
        $this->db->query($sql);
        $this->db->bind(":task_id", $task_id);
        $this->db->bind(":section_id", $section_id);
        $task = $this->db->single();
        if ($task){
            return $task;
        }
            throw new TaskException("Task does not exist",404);

    }

    /**
     * Search for task in the database
     *
     * @param array $params section_id(id), limit(int) is Optional
     * @return array with tasks information
     * @throws Exception
     */

    public function getAll(array $params): array
    {
        $section_id = $params["section_id"];

        $sql = "SELECT * FROM Tasks WHERE section_id= :section_id";
        $this->db->query($sql);

        $this->db->bind(":section_id", $section_id);

        return $this->db->result_set();

    }

    /**
     * Creates a task and save it in the db
     *
     * @param array $params Should have section_id(int) and name(string); status_id(int) and $summary are optional
     * @throws TaskException
     * @throws Exception
     */
    public function createElement(array $params)
    {
        $section_id = $params["section_id"];
        $status_id = array_key_exists("status_id", $params) ? $params["status_id"] : 1;
        $name = array_key_exists("name", $params) ? $params["name"] : null;
        $summary = array_key_exists("summary", $params) ? $params["summary"] : "";
        $user_id = $params["user_id"];

        if ($name){
            $sql = "INSERT INTO Tasks (name, summary, section_id, status_id, user_id)
            VALUE (:name ,:summary, :section_id,:status_id, :user_id)";

            $this->db->query($sql);
            $this->db->bind(":section_id", $section_id);
            $this->db->bind(":name", $name);
            $this->db->bind(":summary", $summary);
            $this->db->bind(":status_id", $status_id);
            $this->db->bind(":user_id", $user_id);

            $this->db->execute();

            $this->success_verification();
        }else {
            throw new TaskException("Something is missing in the request see documentation", 400);
        }
    }

    /**
     * Edit element in the db
     *
     * @param array $params Should have name(string),status_id(int),$summary(string) and section_id(int)
     * @param int $id task id
     * @throws TaskException
     * @throws Exception
     *
     */
    public function editElement($id, $params)
    {
        $status_id = array_key_exists("status_id", $params) ? $params["status_id"] : null;
        $name = array_key_exists("name", $params) ? $params["name"] : null;
        $summary = array_key_exists("summary", $params) ? $params["summary"] : null;
        $section_id = $params["section_id"];
        $user_id = $params["user_id"];

        if ($status_id && $name && $summary && $section_id) {
            $sql = "UPDATE Tasks 
                SET name = :name,
                    summary = :summary,
                    status_id = :status_id
                WHERE id = :id 
                  AND section_id = :section_id
                  AND user_id = :user_id";

            $this->db->query($sql);
            $this->db->bind(":name", $name);
            $this->db->bind(":summary", $summary);
            $this->db->bind(":status_id", $status_id);
            $this->db->bind(":id", $id);
            $this->db->bind(":section_id", $section_id);
            $this->db->bind(":user_id", $user_id);

            $this->db->execute();

            $this->success_verification();
        }else {
            throw new TaskException("Something is missing in the request see documentation", 400);
        }
    }

    /**
     * Hard delete from db
     *
     * @param int $id
     * @param array $params With the id(int) and section id(int)
     * @throws TaskException
     * @throws \Exception
     */
    public function deleteElement($id, $params)
    {
        $user_id = $params["user_id"];

        $sql = "DELETE FROM Tasks 
            WHERE id = :id AND user_id = :user_id";

        $this->db->query($sql);
        $this->db->bind(":id", $id);
        $this->db->bind(":user_id", $user_id);
        $this->db->execute();

        $this->success_verification();
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