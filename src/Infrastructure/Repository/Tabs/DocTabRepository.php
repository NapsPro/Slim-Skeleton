<?php

namespace App\Infrastructure\Repository\Tabs;

use App\Application\Exceptions\TabException;
use App\Entities\Tabs;
use App\Entities\Tickets;
use App\Entities\Users;
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
                ->select("t")
                ->from(Tabs::class,"t")
                ->join("t.ticket","ts")
                ->where("t.id = :id")
                ->andWhere("ts.slug = :slug")
                ->setParameter(":id",$id["id"])
                ->setParameter(":slug",$id["ticket_slug"])
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
                ->select("t")
                ->from(Tabs::class,"t")
                ->join("t.ticket","ts")
                ->where("ts.slug = :slug")
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

        $name = array_key_exists("name", $params) ? $params["name"] : null;

        if ($name) {
            try {
                $user =  $this->em->find(Users::class, $params["user_id"]);
                $ticket = $this->em->getRepository(Tickets::class)->findOneBy(["slug"=>$params["ticket_slug"]]);
                $tab = new Tabs();
                $tab->setUser($user);
                $tab->setName($name);
                $tab->setTicket($ticket);

                $this->em->persist($tab);
                $this->em->flush();

            }catch (Exception $exception){
                throw new TabException($exception->getMessage(),400);
            }
        }else{
            throw new TabException("Something is missing in the request see doc",400);
        }

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
        $user_id = $params["user_id"];
        $name = array_key_exists("name", $params) ? $params["name"] : null;
        if ($name) {
            try {
                $ticket = $this->em->getRepository(Tickets::class)->findOneBy(["slug"=>$params["ticket_slug"]]);
                $this->em->createQueryBuilder()
                    ->update(Tabs::class,"t")
                    ->set("t.name",":name")
                    ->where("t.ticket = :ticket_id")
                    ->andWhere("t.id = :id")
                    ->andWhere("t.user = :user_id")
                    ->setParameters(array(
                        ":name"=>$name,
                        ":ticket_id"=>$ticket->getId(),
                        ":user_id"=>$user_id,
                        ":id"=>$id))
                    ->getQuery()
                    ->execute();
            }catch (Exception $exception){
                throw new TabException($exception->getMessage());
            }

        }else{
            throw new TabException("Something is missing in the request see doc",400);
        }


    }

    /**
     * Hard delete from db
     *
     * @param array $params With the id(int) and ticket_id(int)
     * @throws TabException
     */
    public function deleteElement($id, $params)
    {
        $user_id = $params["user_id"];

        try {
            $ticket = $this->em->getRepository(Tickets::class)->findOneBy(["slug"=>$params["ticket_slug"]]);
            $this->em->createQueryBuilder()
                ->delete(Tabs::class,"t")
                ->where("t.ticket= :ticket_id")
                ->andWhere("t.id = :id")
                ->andWhere("t.user = :user_id")
                ->setParameters(array(
                    ":ticket_id"=>$ticket->getId(),
                    ":user_id"=>$user_id,
                    ":id"=>$id
                ))
                ->getQuery()
                ->execute();

        }catch (Exception $exception){
            throw new TabException($exception->getMessage(),400);
        }

    }

}