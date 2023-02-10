<?php

namespace App\Entities;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;
use OpenApi\Annotations as OA;


/**
 * @ORM\Entity
 * @ORM\Table(name="products")
 */

class Users
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     * @var int
     */
    private $id;

    /**
     * @ORM\Column (type="string", unique=true)
     * @var string
     */
    private $username;

    /**
     * @ORM\Column (type="string")
     * @var string
     */
    private $email;

    /**
     * @ORM\Column (type="string")
     * @var string
     */
    private $password;

    /**
     * @ORM\Column (type="datetime")
     * @var DateTime
     */
    private $created_at;

    public function authenticate($password, $hashPassword): bool
    {
        return password_verify($password, $hashPassword);
    }

    public function hashPassword($password){
        return password_hash($password, PASSWORD_DEFAULT);
    }

    public function getUsername(){
        return $this->username;
    }
}