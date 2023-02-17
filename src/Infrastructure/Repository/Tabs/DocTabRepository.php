<?php

namespace App\Infrastructure\Repository\Tabs;

use App\Application\Exceptions\TabException;
use App\Entities\Tabs;
use App\Entities\Tickets;
use Doctrine\ORM\EntityManagerInterface;
use Exception;

class DocTabRepository implements TabsRepositoryInterface
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }
    /**
     * Search for ticket in the database
     *
     * @param array $id id (int)
     * @throws TabException
     * @return Tabs with tab information
     */
    public function getByID($id):Tabs
    {
        try {
            return $this->em->createQueryBuilder()
                ->select("*")
                ->from(Tabs::class,"t")
                ->where("id = :id")
                ->setParameter(":id",$id)
                ->getQuery()
                ->getSingleResult();

        }catch (Exception $exception){
            throw new TabException($exception->getMessage(),400);
        }
    }

    /**
     * Search for all the tabs in the database associate to a user
     *
     * @param array $params ticket_slug(string),
     * @return array with tabs information
     * @throws TabException
     */
    public function getAll(array $params):array
    {
        $slug = $params["ticket_slug"];
        try {
            return $this->em->createQueryBuilder()
                ->select("*")
                ->from(Tabs::class,"t")
                ->join(Tickets::class,"ts", "WITH", "ts.slug = :slug")
                ->where("t.ticket_id = t.id")
                ->setParameter(":slug",$slug)
                ->getQuery()
                ->execute();
        }catch (Exception $exception){
            throw new TabException($exception->getMessage(), 400);
        }

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
            try {
                $ticket_id = $this->getTicket($ticket_name)->id;
                $tab = new Tabs();
                $tab->setUserId($user_id);
                $tab->setName($name);
                $tab->setTicketId($ticket_id);

                $this->em->persist($tab);
                $this->em->flush();

            }catch (Exception $exception){
                throw new TabException($exception->getMessage(),400);
            }
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
            try {
                $this->em->createQueryBuilder()
                    ->update(Tabs::class,"t")
                    ->join(Tickets::class,"ts","WITH","ts.slug = :slug")
                    ->set("t.name",":name")
                    ->where("ts.slug = :slug")
                    ->andWhere("t.id = :id")
                    ->andWhere("t.user_id = :user_id")
                    ->setParameters(array(
                        ":name"=>$name,
                        ":slug"=>$slug,
                        ":user_id"=>$user_id,
                        ":id"=>$id))
                    ->getQuery()
                    ->execute();
            }catch (Exception $exception){
                throw new TabException($exception->getMessage());
            }

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

        try {
            $this->em->createQueryBuilder()
                ->delete("*")
                ->from(Tabs::class,"t")
                ->join(Tickets::class,"ts")
                ->where("ts.slug = :ticket_slug")
                ->andWhere("t.id = :id")
                ->andWhere("t.user_id = :user_id")
                ->setParameters(array(
                    ":slug"=>$ticket_slug,
                    ":user_id"=>$user_id,
                    ":id"=>$id
                ))
                ->getQuery()
                ->execute();

        }catch (Exception $exception){
            throw new TabException($exception->getMessage(),400);
        }

    }
    public function getTicket($ticket_name){

        return $this->em->createQueryBuilder()
            ->select("*")
            ->from(Tickets::class,"t")
            ->where("slug = :slug")
            ->setParameter(":slug",$ticket_name)
            ->getQuery()
            ->execute();

    }
}