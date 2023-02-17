<?php

namespace App\Entities;

/**
 * @ORM\Entity
 * @ORM\Table(name="Tickets")
 *
 * @OA\Schema (
 *     description = "Ticket Model",
 *     title="Tickets"
 * )
 */
class Tickets
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
     * @JoinColumn(name="status_id", referencedColumnName="id")
     *
     * @OA\Property(type="integer", description="Status of the ticket", title="Status id")
     *
     * @var integer
     */
    private $status_id;

    /**
     * @ORM\ManyToOne(targetEntity="Users")
     * @JoinColumn(name="user_id", referencedColumnName="id")
     *
     * @OA\Property(type="integer", description="User associated with ticket", title="user id")
     *
     * @var integer
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
     * @return int
     */
    public function getStatusId(): int
    {
        return $this->status_id;
    }

    /**
     * @param int $status_id
     */
    public function setStatusId(int $status_id): void
    {
        $this->status_id = $status_id;
    }

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->user_id;
    }

    /**
     * @param int $user_id
     */
    public function setUserId(int $user_id): void
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

}