<?php

namespace App\Entities;


/**
 * @ORM\Entity
 * @ORM\Table(name="Sections")
 */
class Sections
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
     * @JoinColumn(name="tab_id", referencedColumnName="id")
     * @var integer
     */
    private $tab_id;
}