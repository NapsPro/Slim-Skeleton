<?php

namespace App\Entities;

use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;
use OpenApi\Annotations as OA;

/**
 * @ORM\Entity
 * @ORM\Table(name="Tasks")
 *
 * @OA\Schema (
 *     description="Task Model",
 *     title="Tasks"
 * )
 */
class Tasks implements JsonSerializable
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
     * @ORM\ManyToOne(targetEntity="Sections")
     * @ORM\JoinColumn(name="section_id", referencedColumnName="id")
     *
     * @OA\Property(type="integer", description="Section associated with task", title="ID")
     *
     * @var Sections
     */
    private $section;

    /**
     * @ORM\ManyToOne(targetEntity="Status")
     * @ORM\JoinColumn(name="status_id", referencedColumnName="id")
     *
     * @OA\Property(type="integer", description="task status", title="Status id")
     *
     * @var Status
     */
    private $status;

    /**
     * @ORM\ManyToOne(targetEntity="Users")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     *
     * @OA\Property(type="integer", description="User associated with task", title="user id")
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
     * @return Sections
     */
    public function getSection(): Sections
    {
        return $this->section;
    }

    /**
     * @param Sections $section
     */
    public function setSection(Sections $section): void
    {
        $this->section = $section;
    }

    /**
     * @return Status
     */
    public function getStatus(): Status
    {
        return $this->status;
    }

    /**
     * @param Status $status
     */
    public function setStatus(Status $status): void
    {
        $this->status = $status;
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


    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
            'summary'=>$this->getSummary(),
        ];
    }
}