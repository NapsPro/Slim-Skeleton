<?php

namespace App\Infrastructure\Repository\Sections;

use App\Application\Exceptions\SectionException;
use App\Entities\Sections;
use App\Entities\Tabs;
use App\Entities\Users;
use Doctrine\ORM\EntityManagerInterface;
use Exception;

class DocSectionRepository implements SectionRepositoryInterface
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @throws SectionException
     */
    public function getByID($id)
    {
        $section_id = $id["id"];
        $tab_id = $id["tab_id"];

        try {
           return $this->em->createQueryBuilder()
                ->select("s")
                ->from(Sections::class,"s")
                ->where("s.tab = :tab_id")
                ->andWhere("s.id = :id")
                ->setParameters(array(
                    ":id"=>$section_id,
                    ":tab_id"=>$tab_id
                ))
                ->getQuery()
                ->getSingleResult();

        }catch (Exception $exception){
            throw new SectionException($exception->getMessage(),400);
        }

    }

    /**
     * Search for Sections in the database
     *
     * @param array $params tab_id(id)
     * @return array with tasks information
     * @throws SectionException
     */
    public function getAll(array $params):array
    {
        $tab_id = $params["tab_id"];
        try {
            return $this->em->createQueryBuilder()
                ->select("s")
                ->from(Sections::class,"s")
                ->where("s.tab = :tab_id")
                ->setParameter(":tab_id",$tab_id)
                ->getQuery()
                ->execute();
        }catch (Exception $exception){
            throw new SectionException($exception->getMessage(),400);
        }

    }

    /**
     * Creates a Section and save it in the db
     *
     * @param array $params Should have tab_id(int) and name(string)
     *@throws SectionException
     */
    public function createElement(array $params): Sections
    {

        $name = array_key_exists("name", $params) ? $params["name"] : null;
        if ($name) {
            try {
                $tab = $this->em->find(Tabs::class, $params["tab_id"]);
                $user = $this->em->find(Users::class, $params["user_id"]);
                $section = new Sections();
                $section->setName($name);
                $section->setUser($user);
                $section->setTab($tab);
                $this->em->persist($section);
                $this->em->flush();
                return $section;
            }catch (Exception $exception){
                throw new SectionException($exception->getMessage(),400);
            }
        }else{
            throw new SectionException("Something is missing in the request see documentation",400);
        }

    }

    /**
     * Edit element in the db
     *
     * @param array $params name(string) and tab_id(int)
     * @param int $id
     * @throws SectionException
     */
    public function editElement($id, $params)
    {
        $name = array_key_exists("name", $params) ? $params["name"] : null;
        $tab_id = $params["tab_id"];
        $user_id = $params["user_id"];

        if ($name){
            try {
                $this->em->createQueryBuilder()
                    ->update(Sections::class,"s")
                    ->set("s.name",":name")
                    ->where("s.id = :id")
                    ->andWhere("s.tab = :tab_id")
                    ->andWhere("s.user = :user_id")
                    ->setParameters(array(
                        ":name"=>$name,
                        ":tab_id"=>$tab_id,
                        ":user_id"=>$user_id,
                        ":id" =>$id
                    ))
                    ->getQuery()
                    ->execute();
            }catch (Exception $exception){
                throw new SectionException($exception->getMessage(),400);
            }

        }else{
            throw new SectionException("Something is missing in the request see documentation",400);
        }

    }

    /**
     * Hard delete from db
     *
     * @param array $params With the tab_id(int)
     * @param int $id
     * @throws SectionException
     */
    public function deleteElement($id, $params)
    {
        $tab_id = $params["tab_id"];
        $user_id = $params["user_id"];

        try {
            $this->em->createQueryBuilder()
                ->delete(Sections::class,"s")
                ->where("s.tab = :tab_id")
                ->andWhere("s.id = :id")
                ->andWhere("s.user = :user_id")
                ->setParameters(array(
                    ":user_id"=>$user_id,
                    ":tab_id" =>$tab_id,
                    ":id"=>$id
                ))
                ->getQuery()
                ->execute();
        }catch (Exception $exception){
            throw new SectionException($exception->getMessage(),400);
        }
    }
}