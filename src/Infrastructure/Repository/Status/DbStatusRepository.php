<?php

namespace App\Infrastructure\Repository\Status;

use App\Application\Exceptions\StatusException;
use App\Infrastructure\Repository\Database;

class DbStatusRepository implements StatusRepositoryInterface
{
    protected $db;

    public function __construct(Database $db){
        $this->db = $db;
    }

    /**
     * Search for ticket in the database
     *
     * @param array $params id (int)
     * @throws StatusException
     * @return array with status information
     */
    public function getByID($params)
    {
        $status_id = $params["id"];

        $sql = "SELECT * FROM status WHERE id = :status_id";

        $this->db->query($sql);
        $this->db->bind(":status_id", $status_id);

        $status = $this->db->single();

        if ($status){
            return $status;
        }
        throw new StatusException("Status does not exist",404);
    }

    /**
     * Search for task in the database
     *
     * @param array $params user_slug(string)
     * @return array with status information
     */
    public function getAll($params, $queryParam = []): array
    {
        $user_id = explode("-",$params["user_slug"])[1];

        $sql = "SELECT * FROM status WHERE user_id = :user_id OR user_id IS NULL";

        $this->db->query($sql);
        $this->db->bind(":user_id", $user_id);

        return $this->db->result_set();
    }

    /**
     * Creates a ticket and save it in the db
     *
     * @param array $params Should have user_id(int); name(string)
     * @throws StatusException
     * @return bool
     */
    public function create_element($params): bool
    {

        $user_id = $params["user_id"];
        $name = array_key_exists("name", $params) ? $params["name"] : null;

        if ($name) {
            $sql = "INSERT INTO status (name, user_id)
                VALUE (:name,:user_id)";

            $this->db->query($sql);
            $this->db->bind(":user_id", $user_id);
            $this->db->bind(":name", $name);
            $this->db->execute();

            return $this->success_verification();
        }
        throw new StatusException("Something is missing in the request see doc",400);
    }

    /**
     * Edit element in the db
     *
     * @param array $params Should have id(int),name(string)
     * @throws StatusException
     * @return bool
     *
     */
    public function edit_element($params): bool
    {

        $id = $params["id"];
        $name = array_key_exists("name", $params) ? $params["name"] : null;
        if ($name) {
            $sql = "UPDATE status 
                SET name = :name
                WHERE id = :id";

            $this->db->query($sql);
            $this->db->bind(":name", $name);
            $this->db->bind(":id", $id);
            $this->db->execute();

            return $this->success_verification();
        }
        throw new StatusException("Something is missing in the request see doc",400);
    }

    /**
     * Hard delete from db
     *
     * @param array $params With the id(int)
     * @throws StatusException
     * @return bool
     */
    public function delete_element($params): bool
    {
        $id = $params["id"];

        $sql = "DELETE FROM status 
            WHERE id = :id";
        $this->db->query($sql);
        $this->db->bind(":id", $id);
        $this->db->execute();

        return $this->success_verification();
    }

    /**
     * Verify if execution was successful
     *
     * @throws StatusException
     * @return bool
     */
    public function success_verification(): bool
    {
        if ($this->db->row_count()== "0"){
            throw new StatusException("Something went wrong", 500);
        }
        return true;
    }
}