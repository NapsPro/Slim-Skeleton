<?php

namespace App\Entities;

/**
 * @ORM\Entity
 * @ORM\Table(name="Tabs")
 *
 * @OA\Schema (
 *     description="Tab Model",
 *     title="Tabs"
 * )
 */
class Tabs
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
     * @ORM\Column(type="string", length=191)
     *
     * @OA\Property(type="string", description="Tab name", title="Name")
     *
     * @var string
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity="Tickets")
     * @JoinColumn(name="status_id", referencedColumnName="name")
     *
     * @OA\Property(type="integer", description="Ticket associated with tab", title="Ticket id")
     *
     * @var integer
     */
    private $ticket_id;

    /**
     * @ORM\ManyToOne(targetEntity="Users")
     * @JoinColumn(name="user_id", referencedColumnName="id")
     *
     * @OA\Property(type="integer", description="User associated with Tab", title="user id")
     *
     * @var integer
     */
    private $user_id;

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
    public function getTicketId(): int
    {
        return $this->ticket_id;
    }

    /**
     * @param int $ticket_id
     */
    public function setTicketId(int $ticket_id): void
    {
        $this->ticket_id = $ticket_id;
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



}