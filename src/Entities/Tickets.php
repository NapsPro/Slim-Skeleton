<?php

namespace App\Entities;

use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;
use OpenApi\Annotations as OA;

/**
 * @ORM\Entity
 * @ORM\Table(name="Tickets")
 *
 * @OA\Schema (
 *     description = "Ticket Model",
 *     title="Tickets"
 * )
 */
class Tickets implements JsonSerializable
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     *
     * @OA\Property(type="integer", description="ID", title="ID")
     *
     * @var int
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=191, unique=true)
     *
     * @OA\Property(type="string", description="Ticket name", title="Name")
     *
     * @var string
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity="Status")
     * @ORM\JoinColumn(name="status_id", referencedColumnName="id")
     *
     * @OA\Property(type="integer", description="Status of the ticket", title="Status id")
     *
     * @var Status
     */
    private $status;

    /**
     * @ORM\ManyToOne(targetEntity="Users")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     *
     * @OA\Property(type="integer", description="User associated with ticket", title="user id")
     *
     * @var Users
     */
    private $user_id;


    /**
     * @ORM\Column(type="string", length=191, unique=true)
     *
     * @OA\Property(type="string", description="Ticket identifier", title="Ticket slug")
     *
     * @var string
     */
    private $slug;

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
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return Status
     */
    public function getStatusId(): Status
    {
        return $this->status;
    }

    /**
     * @param Status $status
     */
    public function setStatusId(Status $status): void
    {
        $this->status = $status;
    }

    /**
     * @return Users
     */
    public function getUserId(): Users
    {
        return $this->user_id;
    }

    /**
     * @param Users $user_id
     */
    public function setUserId(Users $user_id): void
    {
        $this->user_id = $user_id;
    }

    /**
     * @return string
     */
    public function getSlug(): string
    {
        return $this->slug;
    }

    /**
     * @param string $slug
     */
    public function setSlug(string $slug): void
    {
        $this->slug = $slug;
    }

    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
            "status" =>$this->getStatusId()
        ];
    }
}