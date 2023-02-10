<?php

namespace App\Infrastructure\Repository\Tickets;


use App\Infrastructure\Repository\Database;
use App\Application\Exceptions\TicketException;

class DbTicketRepository implements TicketRepositoryInterface
{
    protected $db;

    public function __construct(Database $db){
        $this->db = $db;
    }

    /**
     * Get all the tickets associated with a user
     *
     * @param array $params should have user_id(id)
     * @throws TicketException
     * @return array Array of arrays with ticket information
     */
    public function getAll($params, $queryParam = []): array
    {
        $user_id = array_key_exists("user_id", $params) ? $params["user_id"] : null;
        if ($user_id){
            $sql = "SELECT * FROM tickets WHERE user_id = :user_id";
            $this->db->query($sql);
            $this->db->bind(":user_id", $user_id);

            return $this->db->result_set();
        }

        throw new TicketException("user_id missing",400);

    }

    /**
     * Search for ticket in the database
     *
     * @param array $params ticket_slug (string)
     * @throws TicketException
     * @return array with ticket information
     */
    public function getByID($params): array
    {
        $slug = $params["ticket_slug"];

        $sql = "SELECT * FROM tickets WHERE slug = :slug";
        $this->db->query($sql);
        $this->db->bind(":slug", $slug);

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
     * @throws TicketException
     * @return bool
     */
    public function create_element($params): bool
    {
        $user_id = array_key_exists("user_id", $params) ? $params["user_id"] : null;
        $status_id = array_key_exists("status_id", $params) ? $params["status_id"] : 1;
        $name = array_key_exists("name", $params) ? $params["name"] : null;

        if ($user_id && $status_id && $name) {
            $sql = "INSERT INTO tickets (name, status_id, user_id, slug) 
                VALUE (:name,:status_id,:user_id,:slug)";

            $slug = "T-" . $name;

            $this->db->query($sql);
            $this->db->bind(":user_id", $user_id);
            $this->db->bind(":name", $name);
            $this->db->bind(":status_id", $status_id);
            $this->db->bind(":slug", $slug);

            $this->db->execute();

            return $this->success_verification();

        }

        throw new TicketException("Something is missing in the request see doc",400);
    }

    /**
     * Edit element in the db
     *
     * @param array $params Should have id(int) and name(string), status_id(id) is optional
     * @throws TicketException
     * @return bool
     *
     */
    public function edit_element($params): bool
    {
        $id = $params["id"];
        $status_id = array_key_exists("status_id", $params) ? $params["status_id"] : 1;
        $name = array_key_exists("name", $params) ? $params["name"] : null;
        if ($status_id && $name) {
            $sql = "UPDATE tickets 
                SET name = :name,
                status_id = :status_id,
                slug = :slug
                WHERE id = :id";

            $slug = "T-".$name;
            $this->db->query($sql);
            $this->db->bind(":id", $id);
            $this->db->bind(":name", $name);
            $this->db->bind(":status_id", $status_id);
            $this->db->bind(":slug", $slug);

            $this->db->execute();

            return $this->success_verification();

        }
        throw new TicketException("Something is missing in the request see doc",400);
    }

    /**
     * Hard delete from db
     *
     * @param array $params With the ticket_slug(string)
     * @throws TicketException
     * @return bool
     */
    public function delete_element($params): bool
    {

        $slug = $params["ticket_slug"];

        $sql = "DELETE FROM tickets 
            WHERE slug = :slug";
        $this->db->query($sql);
        $this->db->bind(":slug", $slug);

        $this->db->execute();

        return $this->success_verification();
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
            throw new TicketException("Something went wrong", 500);
        }
        return true;
    }
}
