<?php

namespace App\Infrastructure\Repository\Tabs;

use App\Application\Exceptions\TabException;
use App\Infrastructure\Repository\Database;

class PdoTabRepository implements TabsRepositoryInterface
{
    protected $db;

    public function __construct(Database $db){
        $this->db = $db;
    }

    /**
     * Search for ticket in the database
     *
     * @param array $id id (int)
     * @throws TabException
     * @return mixed with tab information
     */
    public function getByID($id)
    {

        $sql = "SELECT * FROM Tabs WHERE id = :tab_id";

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
    public function getAll(array $params): array
    {
        $slug = $params["ticket_slug"] ;


        $sql = "SELECT * FROM Tabs t JOIN Tickets ts on ts.id = t.id WHERE ts.slug = :ticket_id";
        $this->db->query($sql);
        $this->db->bind(":ticket_id", $slug);

        return $this->db->result_set();

    }

    /**
     * Creates a tab and save it in the db
     *
     * @param array $params Should have ticket_slug(string); name(string)
     * @throws TabException
     */
    public function createElement(array $params)
    {
        $ticket_name = $params["ticket_slug"];
        $name = array_key_exists("name", $params) ? $params["name"] : null;
        $user_id = $params["user_id"];

        if ($name) {

            $ticket = $this->getTicket($ticket_name);

            $sql = "INSERT INTO Tabs (name, ticket_id, user_id) 
                    VALUE (:name,:ticket_id, :user_id)";

            $this->db->query($sql);
            $this->db->bind(":ticket_id", $ticket->id);
            $this->db->bind(":name", $name);
            $this->db->bind("$:user_id", $user_id);

            $this->db->execute();

            $this->success_verification();
        }
        throw new TabException("Something is missing in the request see doc",400);
    }

    /**
     * Edit element in the db
     *
     * @param array $params Should have id(int),name(string), and ticket_slug(string)
     * @throws TabException
     *
     */
    public function editElement($id, $params)
    {
        $slug = $params["ticket_slug"];
        $user_id = $params["user_id"];
        $name = array_key_exists("name", $params) ? $params["name"] : null;
        if ($name) {
            $sql = "UPDATE Tabs t JOIN Tickets ts on t.ticket_id = ts.id
                SET t.name = :name
                WHERE ts.slug = :slug
                AND t.id = :id
                AND t.user_id = :user_id";

            $this->db->query($sql);
            $this->db->bind(":slug", $slug);
            $this->db->bind(":name", $name);
            $this->db->bind(":id", $id);
            $this->db->bind(":user_id", $user_id);
            $this->db->execute();

            $this->success_verification();
        }

        throw new TabException("Something is missing in the request see doc",400);
    }

    /**
     * Hard delete from db
     *
     * @param array $params With the id(int) and ticket_id(int)
     * @throws TabException
     */
    public function deleteElement($id, $params)
    {
        $ticket_slug = $params["ticket_slug"];
        $user_id = $params["user_id"];


        $sql = "DELETE t.* FROM Tabs t
            JOIN Tickets ts ON t.ticket_id=ts.id
            WHERE ts.slug = :ticket_slug
            AND id = :id
            AND user_id = :user_id";
        $this->db->query($sql);
        $this->db->bind(":ticket_slug", $ticket_slug);
        $this->db->bind(":id", $id);
        $this->db->bind(":user_id", $user_id);
        $this->db->execute();

        $this->success_verification();
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

    public function getTicket($ticket_name){

        $sql = "SELECT * FROM Tickets WHERE slug = :slug";
        $this->db->query($sql);
        $this->db->bind(":slug", $ticket_name);

        return $this->db->single();
    }
}
