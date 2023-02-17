<?php

namespace App\Entities;

/**
 * @ORM\Entity
 * @ORM\Table(name="Tasks")
 *
 * @OA\Schema (
 *     description="Task Model",
 *     title="Tasks"
 * )
 */
class Tasks
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
     * @OA\Property(type="string", description="Task name", title="Name")
     *
     * @var string
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=191)
     *
     * @OA\Property(type="string", description="Task summary", title="Summary")
     *
     * @var string
     */
    private $summary;

    /**
     * @ORM\ManyToOne(targetEntity="Section")
     * @JoinColumn(name="section_id", referencedColumnName="id")
     *
     * @OA\Property(type="integer", description="Section associated with task", title="ID")
     *
     * @var integer
     */
    private $section_id;

    /**
     * @ORM\ManyToOne(targetEntity="Status")
     * @JoinColumn(name="status_id", referencedColumnName="id")
     *
     * @OA\Property(type="integer", description="task status", title="Status id")
     *
     * @var integer
     */
    private $status_id;

    /**
     * @ORM\ManyToOne(targetEntity="Users")
     * @JoinColumn(name="user_id", referencedColumnName="id")
     *
     * @OA\Property(type="integer", description="User associated with task", title="user id")
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
     * @return string
     */
    public function getSummary(): string
    {
        return $this->summary;
    }

    /**
     * @param string $summary
     */
    public function setSummary(string $summary): void
    {
        $this->summary = $summary;
    }

    /**
     * @return int
     */
    public function getSectionId(): int
    {
        return $this->section_id;
    }

    /**
     * @param int $section_id
     */
    public function setSectionId(int $section_id): void
    {
        $this->section_id = $section_id;
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



}