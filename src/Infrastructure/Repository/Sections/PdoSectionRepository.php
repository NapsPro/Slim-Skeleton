<?php

namespace App\Infrastructure\Repository\Sections;


use App\Application\Exceptions\SectionException;
use App\Application\Exceptions\SessionException;
use App\Infrastructure\Repository\Database;

class PdoSectionRepository implements SectionRepositoryInterface {

    protected $db;

    public function __construct(Database $db){
        $this->db = $db;
    }

    /**
     * @throws SectionException
     */
    public function getByID($params)
    {
        $id = $params["id"];
        $tab_id = $params["tab_id"];

        $sql = "SELECT * FROM Sections WHERE id = :section_id 
                     AND tab_id = :tab_id";

        $this->db->query($sql);
        $this->db->bind(":section_id", $id);
        $this->db->bind(":tab_id", $tab_id);

        $section = $this->db->single();

        if ($section){
            return $section;
        }
        throw new SectionException("Section not found", 404);
    }

    /**
     * Search for task in the database
     *
     * @param array $params tab_id(id)
     * @return array with tasks information
     */

    public function getAll($params, $queryParam = []): array
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
     * @return bool
     */
    public function createElement($params): bool
    {
        $tab_id = $params["tab_id"];
        $name = array_key_exists("name", $params) ? $params["name"] : null;

        if ($name) {
            $sql = "INSERT INTO Sections (name, tab_id)
                VALUE (:name,:tab_id)";

            $this->db->query($sql);
            $this->db->bind(":name", $name);
            $this->db->bind(":tab_id", $tab_id);

            $this->db->execute();

            return $this->success_verification();
        }
        throw new SectionException("Something is missing in the request see doc",400);
    }

    /**
     * Edit element in the db
     *
     * @param array $params Should have id(int),name(string) and tab_id(int)
     * @return bool
     * @throws SectionException
     */
    public function editElement($params): bool
    {
        $id = array_key_exists("id", $params) ? $params["id"] : null;
        $name = array_key_exists("name", $params) ? $params["name"] : null;
        $tab_id = array_key_exists("tab_id", $params) ? $params["tab_id"] : null;
        if ($id && $name && $tab_id) {
            $sql = "UPDATE Sections 
                SET name = :name
                WHERE id = :id 
                AND tab_id = :tab_id";


            $this->db->query($sql);
            $this->db->bind(":name", $name);
            $this->db->bind(":id", $id);
            $this->db->bind(":tab_id", $tab_id);

            $this->db->execute();

            return $this->success_verification();
        }
        throw new SectionException("Something is missing in the request see doc",400);
    }

    /**
     * Hard delete from db
     *
     * @param array $params With the id(int) and tab_id(int)
     * @throws SectionException
     * @return bool
     */
    public function deleteElement($params): bool
    {
        $id = array_key_exists("id", $params) ? $params["id"] : null;
        $tab_id = array_key_exists("tab_id", $params) ? $params["tab_id"] : null;
        if ($id && $tab_id) {
            $sql = "DELETE FROM Sections 
                WHERE id = :id
                AND tab_id = :tab_id";
            $this->db->query($sql);
            $this->db->bind(":id", $id);
            $this->db->bind(":tab_id", $tab_id);

            $this->db->execute();

            return $this->success_verification();
        }
        throw new SectionException("Something is missing in the request see doc",400);
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