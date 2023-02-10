<?php

namespace App\Infrastructure\Repository\Tabs;

use App\Application\Exceptions\TabException;
use App\Infrastructure\Repository\Database;

class DbTabRepository implements TabsRepositoryInterface
{
    protected $db;

    public function __construct(Database $db){
        $this->db = $db;
    }

    /**
     * Search for ticket in the database
     *
     * @param array $params id (int)
     * @throws TabException
     * @return array with tab information
     */
    public function getByID($params)
    {
        $id = $params["id"];

        $sql = "SELECT * FROM tabs WHERE id = :tab_id";

        $this->db->query($sql);
        $this->db->bind(":tab_id", $id);

        $tab = $this->db->single();
        if ($tab) {
            return $tab;
        }
        throw new TabException("Tab not found", 404);
    }

    /**
     * Search for all the tabs in the database associate to a user
     *
     * @param array $params ticket_slug(string),
     * @return array with tabs information
     */
    public function getAll($params, $queryParam = []): array
    {
        $slug = $params["ticket_slug"] ;

        $ticket_name = explode("-",$slug)[1];

        $sql = "SELECT * FROM tabs WHERE tabs.ticket_name = :ticket_name";
        $this->db->query($sql);
        $this->db->bind(":ticket_name", $ticket_name);

        return $this->db->result_set();

    }

    /**
     * Creates a tab and save it in the db
     *
     * @param array $params Should have ticket_slug(string); name(string)
     * @throws TabException
     * @return bool
     */
    public function create_element($params): bool
    {
        $ticket_name = explode("-",$params["ticket_slug"])[1];
        $name = array_key_exists("name", $params) ? $params["name"] : null;

        if ($ticket_name && $name) {
            $sql = "INSERT INTO tabs (name, ticket_name) 
                    VALUE (:name,:ticket_name)";

            $this->db->query($sql);
            $this->db->bind(":ticket_name", $ticket_name);
            $this->db->bind(":name", $name);

            $this->db->execute();

            return $this->success_verification();
        }
        throw new TabException("Something is missing in the request see doc",400);
    }

    /**
     * Edit element in the db
     *
     * @param array $params Should have id(int),name(string), and ticket_slug(string)
     * @throws TabException
     * @return bool
     *
     */
    public function edit_element($params): bool
    {
        $ticket_name = explode("-",$params["ticket_slug"])[1];
        $name = array_key_exists("name", $params) ? $params["name"] : null;
        $id = $params["id"];
        if ($ticket_name && $name && $id) {
            $sql = "UPDATE tabs 
                SET name = :name
                WHERE ticket_name = :ticket_name
                AND id = :id";

            $this->db->query($sql);
            $this->db->bind(":ticket_name", $ticket_name);
            $this->db->bind(":name", $name);
            $this->db->bind(":id", $id);
            $this->db->execute();

            return $this->success_verification();
        }

        throw new TabException("Something is missing in the request see doc",400);
    }

    /**
     * Hard delete from db
     *
     * @param array $params With the id(int) and ticket_name(int)
     * @throws TabException
     * @return bool
     */
    public function delete_element($params): bool
    {
        $ticket_name = explode("-",$params["ticket_slug"])[1];;
        $id = array_key_exists("id",$params) ? $params["id"] : null;
        if ($ticket_name && $id){
            $sql = "DELETE FROM tabs 
                WHERE ticket_name = :ticket_id
                AND id = :id";
            $this->db->query($sql);
            $this->db->bind(":ticket_id", $ticket_name);
            $this->db->bind(":id", $id);
            $this->db->execute();

            return $this->success_verification();
        }

        throw new TabException("Something is missing in the request see doc",400);
    }

    /**
     * Verify if execution was successful
     *
     * @throws TabException
     * @return bool
     */
    public function success_verification(): bool
    {
        if ($this->db->row_count()== "0"){
            throw new TabException("Something went wrong", 500);
        }
        return true;
    }
}
