<?php

namespace App\Infrastructure\Repository\Sessions;

use App\Application\Exceptions\SessionException;
use App\Entities\Sessions;
use App\Entities\Users;
use App\Infrastructure\TokenFactory;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
require_once(__DIR__.'/../../../helpers/SecondsToDatetime.php');

class DocSessionRepository implements SessionRepositoryInterface
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * Search for Session in the database
     *
     * @param array $params Should have access_token(string),refresh_token(string) and id(int)
     * @throws SessionException
     * @return Sessions
     */
    public function getByAllFields($params):Sessions
    {
        $access_token = array_key_exists("access_token", $params) ? $params["access_token"] : null;
        $refresh_token = array_key_exists("refresh_token", $params) ? $params["refresh_token"] : null;
        $session_id = array_key_exists("id", $params) ? $params["id"] : null;
        if ($session_id && $refresh_token && $access_token) {
            try {
                return $this->em->createQueryBuilder()
                    ->select("s")
                    ->from(Sessions::class,"s")
                    ->where("s.access_token = :access_token")
                    ->andWhere("s.refresh_token = :refresh_token")
                    ->andWhere("s.id = :session_id")
                    ->setParameters(array(
                        ":access_token" => $access_token,
                        ":refresh_token" => $refresh_token,
                        ":session_id" =>$session_id
                    ))
                    ->getQuery()
                    ->getOneOrNullResult();
            }catch (Exception $exception){
                throw new SessionException($exception->getMessage(),400);
            }

        }
        throw new SessionException("Something is missing in the request see doc", 400);
    }


    /**
     * @throws SessionException
     */
    public function getSession($access_token)
    {
        try {
            $session = $this->em->createQueryBuilder()
                ->select("s")
                ->from(Sessions::class,"s")
                ->where("s.access_token = :access_token")
                ->setParameter(":access_token",$access_token)
                ->getQuery()
                ->getSingleResult();

            if ($session){
                if($session->getRefreshTokenExpire() > new DateTime("now")){
                    return $session;
                };
                throw new SessionException("Access_token_expired",404);
            }
            throw new SessionException("Something went wrong, logging again",404);
        }catch (Exception $exception){
            throw new SessionException($exception->getMessage(),400);
        }

    }

    /**
     * Creates a ticket and save it in the db
     *
     * @param Users $params Should have user_id(int);
     * @return Sessions with the session info
     * @throws Exception
     * @throws SessionException
     */
    public function createSession($params):Sessions
    {

        $data_access = TokenFactory::createToke(1200);
        $data_refresh = TokenFactory::createToke(1209600);

        $access_token = $data_access["token"];
        $refresh_token = $data_refresh["token"];
        $access_token_expire_seconds = $data_access["expires"];
        $refresh_token_expire_seconds = $data_refresh["expires"];

        $session = new Sessions();
        $session->setUser($params);
        $session->setAccessToken($access_token);
        $session->setRefreshToken($refresh_token);
        $session->setAccessTokenExpire($access_token_expire_seconds);
        $session->setRefreshTokenExpire($refresh_token_expire_seconds);

        try {
            $this->em->persist($session);
            $this->em->flush();
            return $session;
        }catch (Exception $exception){
            throw new SessionException($exception->getMessage(),400);
        }

    }


    /**
     * Update session in the db
     *
     * @param array $params Should have access_token(string),refresh_token(string) and session_id(int)
     * @throws SessionException
     * @return Sessions With the new session
     *
     */
    public function updateSession($params): Sessions
    {
        $session = $this->getByAllFields($params);

        if (strtotime($session->getRefreshTokenExpire() < new DateTime("now"))){
            throw new SessionException("refresh token as expired pls log in again", 401);
        }

        $data_access = TokenFactory::createToke(1200000000);
        $data_refresh = TokenFactory::createToke(120960000000);

        $access_token = $data_access["token"];
        $refresh_token = $data_refresh["token"];
        $access_token_expire_seconds = $data_access["expires"];
        $refresh_token_expire_seconds = $data_refresh["expires"];

        try {

            $this->em->createQueryBuilder()
                ->update(Sessions::class,"s")
                ->set("s.refresh_token",":refresh_token")
                ->set("s.access_token" , ":access_token")
                ->set("s.refresh_token_expire",":refresh_token_expire")
                ->set("s.access_token_expire ",":access_token_expire")
                ->where("s.id = :id")
                ->andWhere("s.user = :user_id")
                ->setParameters(array(
                    ":access_token" =>$access_token,
                    ":refresh_token" =>$refresh_token,
                    ":access_token_expire" =>secondsToDatetime($access_token_expire_seconds),
                    ":refresh_token_expire" =>secondsToDatetime($refresh_token_expire_seconds),
                    ":id" =>$session->getId(),
                    ":user_id"=>$session->getUser()->getId()
                ))->getQuery()->execute();



            $session->setAccessToken($access_token);
            $session->setRefreshToken($refresh_token);
            $session->setAccessTokenExpire($access_token_expire_seconds);
            $session->setRefreshTokenExpire($refresh_token_expire_seconds);

            return $session;
        }catch (Exception $exception){
            throw new SessionException($exception->getMessage(),400);
        }
    }

    /**
     * Hard delete from db
     *
     * @param array $params With access_token and user_id
     * @throws SessionException
     */
    public function deleteSession($params)
    {
        $access_token = $params["access_token"];
        $user_id = $params["user_id"];
        try {
            $this->em->createQueryBuilder()
                ->delete(Sessions::class,"s")
                ->where("s.user = :user_id")
                ->andWhere("s.access_token = :access_token")
                ->setParameters(array(
                    ":access_token" =>$access_token,
                    ":user_id"=>$user_id
                ))
                ->getQuery()
                ->execute();
        }catch (Exception $exception){
            throw new SessionException($exception->getMessage(),400);
        }
    }
}