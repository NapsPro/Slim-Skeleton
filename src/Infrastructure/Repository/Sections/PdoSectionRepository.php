<?php

namespace App\Infrastructure\Repository\Sections;


use App\Application\Exceptions\SectionException;
use App\Application\Exceptions\SessionException;
use App\Infrastructure\Repository\Database;
use Exception;

class PdoSectionRepository implements SectionRepositoryInterface {

    protected $db;

    public function __construct(Database $db){
        $this->db = $db;
    }

    /**
     * @throws SectionException
     * @throws Exception
     */
    public function getByID($id)
    {
        $section_id = $id["id"];
        $tab_id = $id["tab_id"];

        $sql = "SELECT * FROM Sections WHERE id = :section_id 
                     AND tab_id = :tab_id";

        $this->db->query($sql);
        $this->db->bind(":section_id", $section_id);
        $this->db->bind(":tab_id", $tab_id);

        $section = $this->db->single();

        if ($section){
            return $section;
        }
        throw new SectionException("Section not found", 404);
    }

    /**
     * Search for Sections in the database
     *
     * @param array $params tab_id(id)
     * @return array with tasks information
     * @throws Exception
     */

    public function getAll(array $params): array
    {
        $tab_id = $params["tab_id"];

        $sql = "SELECT * FROM Sections WHERE tab_id= :tab_id";

        $this->db->query($sql);
        $this->db->bind(":tab_id", $tab_id);

        return $this->db->result_set();
    }

    /**
     * Creates a Section and save it in the db
     *
     * @param array $params Should have tab_id(int) and name(string)
     * @throws SectionException
     * @throws Exception
     */
    public function createElement(array $params)
    {
        $tab_id = $params["tab_id"];
        $user_id = $params["user_id"];
        $name = array_key_exists("name", $params) ? $params["name"] : null;

        if ($name) {
            $sql = "INSERT INTO Sections (name, tab_id, user_id)
                VALUE (:name,:tab_id,:user_id)";

            $this->db->query($sql);
            $this->db->bind(":name", $name);
            $this->db->bind(":tab_id", $tab_id);
            $this->db->bind(":user_id", $user_id);

            $this->db->execute();

           $this->success_verification();
        }else{
            throw new SectionException("Something is missing in the request see doc",400);
        }
    }

    /**
     * Edit element in the db
     *
     * @param array $params name(string) and tab_id(int)
     * @param int $id
     * @throws SectionException
     * @throws Exception
     */
    public function editElement($id, $params)
    {
        $name = array_key_exists("name", $params) ? $params["name"] : null;
        $tab_id =  $params["tab_id"];
        $user_id = $params["user_id"];

        if ($name){
            $sql = "UPDATE Sections 
                SET name = :name
                WHERE id = :id 
                AND tab_id = :tab_id AND user_id = :user_id";


            $this->db->query($sql);
            $this->db->bind(":name", $name);
            $this->db->bind(":id", $id);
            $this->db->bind(":tab_id", $tab_id);
            $this->db->bind(":user_id", $user_id);

            $this->db->execute();

            $this->success_verification();
        }else{
            throw new SectionException("Something is missing in the request see doc",400);
        }

    }

    /**
     * Hard delete from db
     *
     * @param array $params With the tab_id(int)
     * @param int $id
     * @throws SectionException
     */
    public function deleteElement($id, $params)
    {
        $tab_id = $params["tab_id"];
        $user_id = $params["user_id"];


            $sql = "DELETE FROM Sections 
                WHERE id = :id
                AND tab_id = :tab_id AND user_id = :user_id";


        try {
            $this->db->query($sql);
            $this->db->bind(":id", $id);
            $this->db->bind(":tab_id", $tab_id);
            $this->db->bind(":user_id", $user_id);
            $this->db->execute();

        }catch (Exception $exception){
            throw new SectionException($exception->getMessage(),400);
        }

        $this->success_verification();

    }

    /**
     * Verify if execution was successful
     *
     * @throws SectionException
     * @return bool
     */
    public function success_verification(): bool
    {
        if ($this->db->row_count()== "0"){
            throw new SectionException("Something went wrong", 500);
        }
        return true;
    }
}