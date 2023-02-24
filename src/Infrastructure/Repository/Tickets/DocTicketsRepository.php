<?php

namespace App\Infrastructure\Repository\Tickets;

use App\Application\Exceptions\TicketException;
use App\Entities\Status;
use App\Entities\Tickets;
use App\Entities\Users;
use App\helpers\Slug;
use Doctrine\ORM\EntityManagerInterface;
use Exception;

class DocTicketsRepository implements TicketRepositoryInterface
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * Search for ticket in the database
     *
     * @param string $id ticket_slug (string)
     * @throws TicketException
     * @return Tickets
     */
    public function getByID($id): Tickets
    {
        try {
            return $this->em->createQueryBuilder()
                ->select("t")
                ->from(Tickets::class,"t")
                ->where("t.slug = :slug")
                ->setParameter(":slug",$id)
                ->getQuery()
                ->getSingleResult();

        }catch (Exception $e){
            throw new TicketException("Ticket doesnt exist",404);
        }

    }

    /**
     * Get all the tickets associated with a user
     *
     * @param array $params should have user_id(id)
     * @throws TicketException
     * @return mixed Array of arrays with ticket information
     */
    public function getAll(array $params, array $queryParam = [])
    {
        try {
            $user_id = $params["user_id"];
            return $this->em->createQueryBuilder()
                ->select("t")
                ->from(Tickets::class,"t")
                ->where("t.user_id = :user_id")
                ->setParameter(":user_id",$user_id)
                ->getQuery()
                ->execute();
        }catch (Exception $exception){
            throw new TicketException($exception->getMessage(),400);
        }

    }

    /**
     * Creates a ticket and save it in the db
     *
     * @param array $params Should have user_id(int) and name(string), status_id(int) optional
     * @throws TicketException
     */
    public function createElement(array $params)
    {
        $status_id = array_key_exists("status_id", $params) ? $params["status_id"] : 1;
        $user =  $this->em->find(Users::class, $params["user_id"]);
        $status = $this->em->find(Status::class,$status_id);
        $name = array_key_exists("name", $params) ? $params["name"] : null;

        if ($name) {
            $slug = Slug::slugify($name);
            $ticket = new Tickets();
            $ticket->setName($name);
            $ticket->setUserId($user);
            $ticket->setStatusId($status);
            $ticket->setSlug($slug);
            try {
                $this->em->persist($ticket);
                $this->em->flush();
            }catch (Exception $e){
                throw new TicketException($e->getMessage(), 500);
            }

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
    public function editElement($id, $params)
    {

            $user_id = $params["user_id"];
            $slug_id = $id;
            $status_id = array_key_exists("status_id", $params) ? $params["status_id"] : 1;
            $name = array_key_exists("name", $params) ? $params["name"] : null;
            if ($name) {
                try {
                    $this->em->createQueryBuilder()
                        ->update(Tickets::class, "t")
                        ->set("t.name", ":name")
                        ->set("t.status", ":status_id")
                        ->set("t.slug", ":slug")
                        ->where("t.slug = :slug_id")
                        ->andWhere("t.user_id = :user_id")
                        ->setParameters(array(
                            ":slug" => Slug::slugify($name),
                            ":status_id" => $status_id,
                            ":user_id" => $user_id,
                            ":name" => $name,
                            ":slug_id" => $slug_id))
                        ->getQuery()
                        ->execute();
                }catch (Exception $exception){
                    throw new TicketException($exception->getMessage(),400);
            }

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

        try {
            $slug = $id;
            $user_id = $params["user_id"];
            $this->em->createQueryBuilder()
                ->delete(Tickets::class,"t")
                ->where("t.slug = :slug_id")
                ->andWhere("t.user_id = :user_id")
                ->setParameters(array(":user_id"=> $user_id, ":slug_id"=>$slug))
                ->getQuery()
                ->execute();

        }catch (Exception $e){
            throw new TicketException($e->getMessage(),404);
        }

    }
}