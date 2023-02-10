<?php

namespace App\Entities;

/**
 * @ORM\Entity
 * @ORM\Table(name="Tasks")
 */
class Tasks
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
     * @ORM\Column(type="string", length=191)
     * @var string
     */
    private $summary;

    /**
     * @ORM\ManyToOne(targetEntity="Section")
     * @JoinColumn(name="section_id", referencedColumnName="id")
     * @var integer
     */
    private $section_id;

    /**
     * @ORM\ManyToOne(targetEntity="Status")
     * @JoinColumn(name="status_id", referencedColumnName="id")
     * @var integer
     */
    private $status_id;

}