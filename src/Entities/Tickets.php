<?php

namespace App\Entities;

/**
 * @ORM\Entity
 * @ORM\Table(name="Tickets")
 */
class Tickets
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @var int
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=191, unique=true)
     * @var string
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity="Status")
     * @JoinColumn(name="status_id", referencedColumnName="id")
     * @var integer
     */
    private $status_id;

    /**
     * @ORM\ManyToOne(targetEntity="Users")
     * @JoinColumn(name="user_id", referencedColumnName="id")
     * @var integer
     */
    private $user_id;


    /**
     * @ORM\Column(type="string", length=191, unique=true)
     * @var string
     */
    private $slug;

}