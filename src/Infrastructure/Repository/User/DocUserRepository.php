<?php

namespace App\Infrastructure\Repository\User;

use App\Application\Exceptions\UserException;
use App\Entities\Users;
use Doctrine\ORM\EntityManagerInterface;
use Exception;

class DocUserRepository implements UserRepositoryInterface
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @inheritDoc
     */
    public function findUserByUsernamePassword(array $params):Users
    {
        $password = array_key_exists("password", $params) ? $params["password"] : null;
        $username = array_key_exists("username", $params) ? $params["username"] : null;

        if ($password && $username){
            try {
                $row = $this->em->createQueryBuilder()
                    ->select("*")
                    ->from(Users::class,"u")
                    ->where("u.username = :username")
                    ->setParameter(":username",$username)
                    ->getQuery()
                    ->getSingleResult();

                $hash_pass = $row->password;
                if (password_verify($password, $hash_pass)){
                    return $row;
                }
                throw new UserException("Password or username is incorrect", 400);
            }catch (Exception $e){
                throw new UserException($e->getMessage(),500);
            }

        }
        throw new UserException("Password or username missing", 400);
    }

    /*
     * @inheritDoc
     */
    public function registerUser($params):bool
    {
        $password = array_key_exists("password", $params) ? $params["password"] : null;
        $username = array_key_exists("username", $params) ? $params["username"] : null;
        $email = array_key_exists("email", $params) ? $params["email"] : null;
        if ($password && $username && $email ) {
            $user = new Users();
            $user->setEmail($email);
            $user->setPassword(password_hash($password, PASSWORD_DEFAULT));
            $user->setUsername($username);
            $this->em->persist($user);
            $this->em->flush();

        }
        throw new UserException("Password, username or email is missing", 400);
    }
}