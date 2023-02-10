<?php

namespace App\Entities;

/**
 * @ORM\Entity
 * @ORM\Table(name="Tabs")
 */
class Tabs
{

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @var int
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=191)
     * @var string
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity="Tickets")
     * @JoinColumn(name="status_id", referencedColumnName="name")
     * @var integer
     */
    private $ticket_name;

}