<?php

namespace App\Infrastructure\Repository\Tickets;


use App\helpers\Slug;
use App\Infrastructure\Repository\Database;
use App\Application\Exceptions\TicketException;

class PdoTicketRepository implements TicketRepositoryInterface
{
    protected $db;

    public function __construct(Database $db){
        $this->db = $db;
    }

    /**
     * Get all the tickets associated with a user
     *
     * @param array $params should have user_id(id)
     * @return array Array of arrays with ticket information
     */
    public function getAll(array $params): array
    {
        $user_id = $params["user_id"];

        $sql = "SELECT * FROM Tickets WHERE user_id = :user_id";
        $this->db->query($sql);
        $this->db->bind(":user_id", $user_id);

        return $this->db->result_set();
    }

    /**
     * Search for ticket in the database
     *
     * @param string $id ticket_slug (string)
     * @return mixed with ticket information
     * @throws TicketException
     */
    public function getByID($id)
    {

        $sql = "SELECT * FROM Tickets WHERE slug = :slug";
        $this->db->query($sql);
        $this->db->bind(":slug", $id);

        $ticket = $this->db->single();
        if ($ticket){
           return $ticket;
        }

        throw new TicketException("Ticket does not exist", 404);
    }

    /**
     * Creates a ticket and save it in the db
     *
     * @param array $params Should have user_id(int) and name(string), status_id(int) optional
     *@throws TicketException
     */
    public function createElement(array $params)
    {
        $user_id = array_key_exists("user_id", $params) ? $params["user_id"] : null;
        $status_id = array_key_exists("status_id", $params) ? $params["status_id"] : 1;
        $name = array_key_exists("name", $params) ? $params["name"] : null;

        if ($user_id && $status_id && $name) {
            $sql = "INSERT INTO Tickets (name, status_id, user_id, slug) 
                VALUE (:name,:status_id,:user_id,:slug)";

            $slug = Slug::slugify($name);

            $this->db->query($sql);
            $this->db->bind(":user_id", $user_id);
            $this->db->bind(":name", $name);
            $this->db->bind(":status_id", $status_id);
            $this->db->bind(":slug", $slug);

            $this->db->execute();

            $this->success_verification();

        }else{
            throw new TicketException("Something is missing in the request see doc",400);
        }


    }

    /**
     * Edit element in the db
     *
     * @param array $params Should have user_id(int) and name(string), status_id(id) is optional
     * @param string $id ticket_slug (string)
     * @throws TicketException
     *
     */
    public function editElement($id, $params)
    {
        $user_id = $params["user_id"];
        $slug_id = $id;
        $status_id = array_key_exists("status_id", $params) ? $params["status_id"] : 1;
        $name = array_key_exists("name", $params) ? $params["name"] : null;
        if ($name) {
            $sql = "UPDATE Tickets 
                SET name = :name,
                status_id = :status_id,
                slug = :slug
                WHERE slug = :slug_id AND
                      user_id = :user_id";

            $slug = Slug::slugify($name);
            $this->db->query($sql);
            $this->db->bind(":user_id", $user_id);
            $this->db->bind(":slug_id", $slug_id);
            $this->db->bind(":name", $name);
            $this->db->bind(":status_id", $status_id);
            $this->db->bind(":slug", $slug);

            $this->db->execute();

            $this->success_verification();

        }else{
            throw new TicketException("Something is missing in the request see doc",400);
        }

    }

    /**
     * Hard delete from db
     *
     * @param string $id ticket_slug (string)
     * @param array $params Should have user_id(int)
     * @throws TicketException
     */
    public function deleteElement($id, $params)
    {

        $slug = $id;
        $user_id = $params["user_id"];

        $sql = "DELETE FROM Tickets 
            WHERE slug = :slug AND user_id = :user_id";
        $this->db->query($sql);
        $this->db->bind(":slug", $slug);
        $this->db->bind(":user_id", $user_id);

        $this->db->execute();

        $this->success_verification();
    }

    /**
     * Verify if execution was successful
     *
     * @throws TicketException
     * @return bool
     */
    public function success_verification(): bool
    {
        if ($this->db->row_count()== "0"){
            throw new TicketException("Something went wrong", 404);
        }
        return true;
    }
}
