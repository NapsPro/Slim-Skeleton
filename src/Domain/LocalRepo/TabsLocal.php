<?php

namespace App\Domain\LocalRepo;

use JsonSerializable;

class TabsLocal implements JsonSerializable
{
    private  $id;
    private  $name;
    private  $ticket_id;

    public function __construct($id, $name, $ticket_id)
    {
        $this->id = $id;
        $this->name = $name;
        $this->ticket_id = $ticket_id;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
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


    public function jsonSerialize()
    {
        // TODO: Implement jsonSerialize() method.
    }
}
