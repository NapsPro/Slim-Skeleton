<?php

namespace App\Entities;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;
use OpenApi\Annotations as OA;

/**
 * @ORM\Entity
 * @ORM\Table(name="Users")
 *
 * @OA\Schema (
 *     description="User Model",
 *     title="Users"
 * )
 */

class Users implements JsonSerializable
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     *
     * @OA\Property(type="integer", description="ID", title="ID")
     * @var int
     */
    private $id;

    /**
     * @ORM\Column (type="string", unique=true)
     *
     * @OA\Property(type="string", description="Username", title="Username")
     *
     * @var string
     */
    private $username;

    /**
     * @ORM\Column (type="string")
     *
     * @OA\Property(type="string", description="Email", title="Email")
     *
     * @var string
     */
    private $email;

    /**
     * @ORM\Column (type="string")
     *
     * @OA\Property(type="string", description="Password", title="Password")
     *
     * @var string
     */
    private $password;

    /**
     * @ORM\Column (type="datetime")
     *
     * @OA\Property(type="datetime", description="When it was created", title="Created at")
     *
     * @var DateTime
     */
    private $created_at;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @param string $username
     */
    public function setUsername(string $username): void
    {
        $this->username = $username;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    /**
     * @return DateTime
     */
    public function getCreatedAt(): DateTime
    {
        return $this->created_at;
    }

    public function setCreatedAt(): void
    {
        $this->created_at = new DateTime("now");;
    }

    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        return [
            'id' => $this->getId(),
            'username' => $this->getUsername(),
            "email"=> $this->getEmail()
        ];
    }
}