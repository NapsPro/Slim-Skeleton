<?php

namespace App\Entities;

use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;
use OpenApi\Annotations as OA;

/**
 * @ORM\Entity
 * @ORM\Table(name="Tabs")
 *
 * @OA\Schema (
 *     description="Tab Model",
 *     title="Tabs"
 * )
 */
class Tabs implements JsonSerializable
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
     * @ORM\JoinColumn(name="ticket_id", referencedColumnName="id")
     *
     * @OA\Property(type="integer", description="Ticket associated with tab", title="Ticket id")
     *
     * @var Tickets
     */
    private $ticket;

    /**
     * @ORM\ManyToOne(targetEntity="Users")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     *
     * @OA\Property(type="integer", description="User associated with Tab", title="user id")
     *
     * @var Users
     */
    private $user;

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
     * @return Tickets
     */
    public function getTicket(): Tickets
    {
        return $this->ticket;
    }

    /**
     * @param Tickets $ticket
     */
    public function setTicket(Tickets $ticket): void
    {
        $this->ticket = $ticket;
    }

    /**
     * @return Users
     */
    public function getUser(): Users
    {
        return $this->user;
    }

    /**
     * @param Users $user
     */
    public function setUser(Users $user): void
    {
        $this->user = $user;
    }


    public function jsonSerialize()
    {
        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
            "ticket" => $this->getTicket()
        ];
    }
}