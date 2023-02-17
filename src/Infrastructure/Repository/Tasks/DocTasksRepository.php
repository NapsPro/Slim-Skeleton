<?php

namespace App\Infrastructure\Repository\Tasks;

use App\Application\Exceptions\TaskException;
use App\Application\Exceptions\TicketException;
use App\Entities\Tasks;
use Doctrine\ORM\EntityManagerInterface;
use Exception;

class DocTasksRepository implements TasksRepositoryInterface
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * Search for ticket in the database
     *
     * @param array $id id (int) and section_id(int)
     * @throws TaskException
     * @return Tasks
     */
    public function getByID($id): Tasks
    {
        try {
            $task_id = $id["id"];
            $section_id = $id["section_id"];
            return $this->em->createQueryBuilder()
                ->select("*")
                ->from(Tasks::class,"t")
                ->where("t.task_id = :task_id")
                ->andWhere("t.section_id = :section_id")
                ->setParameters(array(":task_id"=>$task_id,":section_id"=>$section_id))
                ->getQuery()
                ->getSingleResult();
        }catch (Exception $exception){

            throw new TaskException($exception->getMessage(),404);
        }

    }

    /**
     * Get all the tickets associated with a user
     *
     * @param array $params should have user_id(id)
     * @throws TicketException
     * @return mixed Array of arrays with ticket information
     */
    public function getAll(array $params)
    {
        try {
            $section_id = $params["section_id"];
            return $this->em->createQueryBuilder()
                ->select("*")
                ->from(Tasks::class,"t")
                ->where("section_id= :section_id")
                ->setParameter(":section_id",$section_id)
                ->getQuery()
                ->execute();
        }catch (Exception $exception){
            throw new TicketException($exception->getMessage(),400);
        }


    }

    /**
     * Creates a task and save it in the db
     *
     *@param array $params Should have section_id(int) and name(string); status_id(int) and $summary are optional
     *@throws TaskException
     */
    public function createElement(array $params)
    {

        $section_id = $params["section_id"];
        $status_id = array_key_exists("status_id", $params) ? $params["status_id"] : 1;
        $name = array_key_exists("name", $params) ? $params["name"] : null;
        $summary = array_key_exists("summary", $params) ? $params["summary"] : "";
        $user_id = $params["user_id"];
        if ($name) {
                $task = new Tasks();
                $task->setStatusId($status_id);
                $task->setSummary($summary);
                $task->setStatusId($status_id);
                $task->setUserId($user_id);
                $task->setSectionId($section_id);
            try {
                $this->em->persist($task);
                $this->em->flush();
            }catch (Exception $exception){
                throw new TaskException($exception->getMessage(),400);
            }
        }
        throw new TaskException("Something is missing in the request see documentation",400);
    }

    /**
     * Edit element in the db
     *
     * @param array $params Should have name(string),status_id(int),$summary(string) and section_id(int)
     * @param int $id task id
     * @throws TaskException
     *
     */
    public function editElement($id, $params)
    {
        $status_id = array_key_exists("status_id", $params) ? $params["status_id"] : null;
        $name = array_key_exists("name", $params) ? $params["name"] : null;
        $summary = array_key_exists("summary", $params) ? $params["summary"] : null;
        $section_id = $params["section_id"];
        $user_id = $params["user_id"];
        if ($status_id && $name && $summary) {
            try {
                $this->em->createQueryBuilder()
                    ->update(Tasks::class, "t")
                    ->set("t.name", ":name")
                    ->set("t.summary", ":summary")
                    ->set("t.status", ":status_id")
                    ->where("id = :id")
                    ->andWhere("section_id = :section_id")
                    ->andWhere("user_id = :user_id")
                    ->setParameters(array(
                        ":summary" => $summary,
                        ":status_id" => $status_id,
                        ":user_id" => $user_id,
                        ":name" => $name,
                        ":id" => $id,
                        ":section_id" => $section_id))
                    ->getQuery()
                    ->execute();
            }catch (Exception $exception){
                throw new TaskException($exception->getMessage(),404);
            }
        }
        throw new TaskException("Something is missing in the request see documentation",400);
    }

    /**
     * Hard delete from db
     *
     * @param int $id
     * @param array $params With the user_id(int)
     * @throws TaskException
     */
    public function deleteElement($id, $params)
    {
        $user_id = $params["user_id"];

        try {
            $this->em->createQueryBuilder()
                ->delete(Tasks::class,"t")
                ->where("t.id = :id")
                ->andWhere("t.user_id = :user_id")
                ->setParameters(array(":user_id"=> $user_id, ":id"=>$id))
                ->getQuery()
                ->execute();
        }catch (Exception $exception){
            throw new TaskException($exception->getMessage(),400);
        }

    }
}