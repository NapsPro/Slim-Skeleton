<?php

namespace App\Infrastructure\Repository\Status;

use App\Application\Exceptions\StatusException;
use App\Infrastructure\Repository\Database;

class PdoStatusRepository implements StatusRepositoryInterface
{
    protected $db;

    public function __construct(Database $db){
        $this->db = $db;
    }

    /**
     * Search for ticket in the database
     *
     * @param int $id
     * @return mixed
     * @throws \Exception
     * @throws StatusException
     */
    public function getByID($id)
    {

        $sql = "SELECT * FROM Status WHERE id = :status_id";

        $this->db->query($sql);
        $this->db->bind(":status_id", $id);

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
     * @throws \Exception
     */
    public function getAll(array $params): array
    {

        $user_id = $params["user_id"];
        $sql = "SELECT * FROM Status WHERE user_id = :user_id OR user_id IS NULL";

        $this->db->query($sql);
        $this->db->bind(":user_id", $user_id);

        return $this->db->result_set();
    }

    /**
     * Creates a Status and save it in the db
     *
     * @param array $params Should have user_id(int); name(string)
     * @throws StatusException
     * @throws \Exception
     */
    public function createElement(array $params)
    {

        $user_id = $params["user_id"];
        $name = array_key_exists("name", $params) ? $params["name"] : null;

        if ($name) {
            $sql = "INSERT INTO Status (name, user_id)
                VALUE (:name,:user_id)";

            $this->db->query($sql);
            $this->db->bind(":user_id", $user_id);
            $this->db->bind(":name", $name);
            $this->db->execute();

            $this->success_verification();
        }else{
            throw new StatusException("Something is missing in the request see doc",400);
        }

    }

    /**
     * Edit element in the db
     *
     * @param array $params Should have id(int),name(string)
     * @param int $id
     * @throws StatusException
     * @throws \Exception
     *
     */
    public function editElement($id, $params)
    {
        $user_id = $params["user_id"];
        $name = array_key_exists("name", $params) ? $params["name"] : null;
        if ($name) {
            $sql = "UPDATE Status 
                SET name = :name
                WHERE id = :id 
                AND user_id = :user_id";

            $this->db->query($sql);
            $this->db->bind(":name", $name);
            $this->db->bind(":id", $id);
            $this->db->bind(":user_id", $user_id);
            $this->db->execute();

            $this->success_verification();
        }else{
            throw new StatusException("Something is missing in the request see doc",400);
        }

    }

    /**
     * Hard delete from db
     *
     * @param array $params With the id(int)
     * @param int $id
     * @throws StatusException
     * @throws \Exception
     */
    public function deleteElement($id, $params)
    {
        $user_id = $params["user_id"];

        $sql = "DELETE FROM Status 
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