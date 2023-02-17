<?php

namespace App\Infrastructure\Repository\Sessions;
use App\Application\Exceptions\SessionException;
use App\Infrastructure\Repository\Database;
use App\Infrastructure\TokenFactory;



class PdoSessionRepository implements SessionRepositoryInterface
{

    protected $db;

    public function __construct(Database $db){
        $this->db = $db;
    }

    /**
     * Search for Session in the database
     *
     * @param array $params Should have access_token(string),refresh_token(string) and session_id(int)
     * @throws SessionException
     * @return mixed with session information
     */
    public function getByAllFields($params)
    {
        $access_token = array_key_exists("access_token", $params) ? $params["access_token"] : null;
        $refresh_token = array_key_exists("refresh_token", $params) ? $params["refresh_token"] : null;
        $session_id = array_key_exists("session_id", $params) ? $params["session_id"] : null;
        if ($session_id && $refresh_token && $access_token) {
            $sql = "SELECT * from Sessions where access_token = :access_token 
                         AND refresh_token = :refresh_token 
                         AND id = :session_id";
            $this->db->query($sql);
            $this->db->bind(":access_token", $access_token);
            $this->db->bind(":refresh_token", $refresh_token);
            $this->db->bind(":session_id", $session_id);

            $session = $this->db->single();
            if ($session){
                return $session;
            }
            throw new SessionException("Session not found", 400);
        }
        throw new SessionException("Something is missing in the request see doc", 400);
    }

    public function getAll(array $params, array $queryParam = [])
    {
        // TODO: Implement getAll() method. Only admin can see them all
    }

    /**
     * Creates a ticket and save it in the db
     *
     * @param object $params Should have user_id(int);
     * @throws SessionException
     * @return array with the session info
     */
    public function createSession($params): array
    {
        $user_id = $params->id;
        $data_access = TokenFactory::createToke(1200);
        $data_refresh = TokenFactory::createToke(1209600);

        $access_token = $data_access["token"];
        $refresh_token = $data_refresh["token"];
        $access_token_expire_seconds = $data_access["expires"];
        $refresh_token_expire_seconds = $data_refresh["expires"];
        if ($user_id){
            $sql = "INSERT INTO Sessions (user_id, access_token, access_token_expire, refresh_token, refresh_token_expire)
                 VALUE (:user_id, :access_token, :access_token_expire, 
                        :refresh_token, :refresh_token_expire)";

            $this->db->query($sql);
            $this->db->bind(":user_id", $user_id);
            $this->db->bind(":access_token", $access_token);
            $this->db->bind(":refresh_token", $refresh_token);
            $this->db->bind(":access_token_expire", date("Y-m-d H:i:s",$access_token_expire_seconds));
            $this->db->bind(":refresh_token_expire", date("Y-m-d H:i:s",$refresh_token_expire_seconds));
            $return_value = [];
            $this->db->execute();

            $this->success_verification();

            $return_value["session_id"] = $this->db->dbh->lastInsertId();
            $return_value["access_token"] = $access_token;
            $return_value["access_token_expires_in"] = $access_token_expire_seconds;
            $return_value["refresh_token"] = $refresh_token;
            $return_value["refresh_token_expires_in"] = $refresh_token_expire_seconds;

            return $return_value;

        }

         throw new SessionException("Something is missing in the request see doc", 400);
    }

    /**
     * Update session in the db
     *
     * @param array $params Should have access_token(string),refresh_token(string) and session_id(int)
     * @throws SessionException
     * @return array With the new session
     *
     */
    public function updateSession($params): array
    {
        $session = $this->getByAllFields($params);

        if (strtotime($session["refresh_token_expire"]) < time()){
            throw new SessionException("refresh token as expired pls log in again", 401);
        }

        $data_access = TokenFactory::createToke(1200);
        $data_refresh = TokenFactory::createToke(1209600);

        $access_token = $data_access["token"];
        $refresh_token = $data_refresh["token"];
        $access_token_expire_seconds = $data_access["expires"];
        $refresh_token_expire_seconds = $data_refresh["expires"];

        $sql = "UPDATE Sessions SET access_token = :access_token,
            refresh_token = :refresh_token,  
            access_token_expire = :access_token_expire,
            refresh_token_expire = :refresh_token_expire
            WHERE id = :session_id AND 
                  user_id = :user_id";

        $this->db->query($sql);
        $this->db->bind(":access_token",$access_token);
        $this->db->bind(":refresh_token",$refresh_token);
        $this->db->bind(":refresh_token_expire",date("Y-m-d H:i:s",$refresh_token_expire_seconds));
        $this->db->bind(":access_token_expire",date("Y-m-d H:i:s",$access_token_expire_seconds));
        $this->db->bind(":session_id",$session["id"]);
        $this->db->bind(":user_id",$session["user_id"]);
        $this->db->execute();

        $this->success_verification();
        return array(
            "id"=>$session["id"],
            "user_id" => $session["user_id"],
            "refresh_token"=> $refresh_token,
            "access_token"=> $access_token,
            "access_token_expires_in" => $access_token_expire_seconds,
            "refresh_token_expires_in"=>$refresh_token_expire_seconds
        );
    }

    /**
     * Hard delete from db
     *
     * @param array $params With the access_token(string)
     * @throws SessionException
     */
    public function deleteSession($params)
    {

        $access_token = $params["token"];
        $user_id = $params["user_id"];

        $sql = "DELETE from Sessions where access_token = :access_token AND user_id = :user_id";
        $this->db->query($sql);
        $this->db->bind(":access_token",$access_token);
        $this->db->bind(":user_id",$user_id);
        $this->db->execute();

        $this->success_verification();
    }

    /**
     * Verify if execution was successful
     *
     * @throws SessionException
     * @return bool
     */
    public function success_verification(): bool
    {
        if ($this->db->row_count()== "0"){
            throw new SessionException("Something went wrong", 500);
        }
        return true;
    }


    /**
     * @throws SessionException
     */
    public function getSession($access_token)
    {

        $sql ="Select * FROM Sessions WHERE access_token=:access_token";
        $this->db->query($sql);
        $this->db->bind("access_token",$access_token);
        $session = $this->db->single();
        if ($session){
            if(strtotime($session->access_token_expire) > time()){
                return $session;
            };
            throw new SessionException("Access_token_expired",404);
        }
        throw new SessionException("Something went wrong, logging again",404);
    }
}