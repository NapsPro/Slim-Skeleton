<?php

namespace App\Entities;

/**
 * @ORM\Entity
 * @ORM\Table(name="Status")
 */
class Status
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
     * @ORM\ManyToOne(targetEntity="Users")
     * @JoinColumn(name="user_id", referencedColumnName="id")
     * @var integer
     */
    private $user_id;

}