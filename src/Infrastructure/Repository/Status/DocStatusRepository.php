<?php

namespace App\Infrastructure\Repository\Status;

use App\Application\Exceptions\StatusException;
use App\Entities\Status;
use App\Entities\Users;
use Doctrine\ORM\EntityManagerInterface;
use Exception;

class DocStatusRepository implements StatusRepositoryInterface
{

    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * Search for ticket in the database
     *
     * @param int $id
     * @throws StatusException
     * @return Status
     */
    public function getByID($id): Status
    {
        try {
            return $this->em->createQueryBuilder()
                ->select("s")
                ->from(Status::class,"s")
                ->where("s.id = :id")
                ->setParameter(":id",$id)
                ->getQuery()
                ->getSingleResult();
        }catch (Exception $exception){
            throw new StatusException($exception->getMessage(),400);
        }

    }

    /**
     * Search for task in the database
     *
     * @param array $params user_id
     * @throws StatusException
     * @return array with status information
     */
    public function getAll(array $params):array
    {
        try {
            $user_id = $params["user_id"];
            return $this->em->createQueryBuilder()
                        ->select("s")
                        ->from(Status::class, "s")
                        ->where("s.user = :user_id")
                        ->setParameter(":user_id", $user_id)
                        ->getQuery()
                        ->execute();
        }catch (Exception $exception){
            throw new StatusException($exception->getMessage(),400);
        }
    }


    /**
     * Creates a Status and save it in the db
     *
     * @param array $params Should have user_id(int); name(string)
     *@throws StatusException
     */
    public function createElement(array $params)
    {

        $name = array_key_exists("name", $params) ? $params["name"] : null;

        if ($name) {
            try {
                $status = new Status();
                $status->setName($name);
                $user = $this->em->find(Users::class, $params["user_id"]);
                $status->setUser($user);
                $this->em->persist($status);
                $this->em->flush();
            }catch (Exception $exception){
                throw new StatusException($exception->getMessage(),400);
            }
        }else {
            throw new StatusException("Something is missing in the request see documentation", 400);
        }
    }

    /**
     * Edit element in the db
     *
     * @param array $params Should have id(int),name(string)
     * @param int $id
     * @throws StatusException
     *
     */
    public function editElement($id, $params)
    {
        $user_id = $params["user_id"];
        $name = array_key_exists("name", $params) ? $params["name"] : null;
        if ($name) {
            try {
                $this->em->createQueryBuilder()
                    ->update(Status::class,"s")
                    ->set("s.name",":name")
                    ->where("s.id = :id")
                    ->andWhere("s.user = :user_id")
                    ->setParameters(array(
                        ":name"=>$name,
                        ":id"=>$id,
                        ":user_id"=>$user_id
                    ))
                    ->getQuery()
                    ->execute();
            }catch (Exception $exception){
                throw new StatusException($exception->getMessage(),400);
            }

        }else{
            throw new StatusException("Something is missing in the request see documentation",400);
        }
    }

    /**
     * Hard delete from db
     *
     * @param array $params With the id(int)
     * @param int $id
     * @throws StatusException
     */
    public function deleteElement($id, $params)
    {
        $user_id = $params["user_id"];

        try {
            $this->em->createQueryBuilder()
                ->delete(Status::class,"s")
                ->where("s.id = :id")
                ->andWhere("s.user = :user_id")
                ->setParameters(array(":id"=>$id, ":user_id"=>$user_id))
                ->getQuery()
                ->execute();
        }catch (Exception $exception){
            throw new StatusException($exception->getMessage(),400);
        }

    }
}