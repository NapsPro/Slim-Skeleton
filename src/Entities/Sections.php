<?php

namespace App\Entities;




use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;
use OpenApi\Annotations as OA;

/**
 * @ORM\Entity
 * @ORM\Table(name="Sections")
 * @OA\Schema (
 *     description = "Section Model",
 *     title="Sections"
 * )
 */
class Sections implements JsonSerializable
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
     * @OA\Property(type="string", description="Section Name", title="Name")
     *
     * @var string
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity="Tabs")
     * @ORM\JoinColumn(name="tab_id", referencedColumnName="id")
     *
     * @OA\Property(type="integer", description="Tab associated", title="Tab id")
     *
     * @var Tabs
     */
    private $tab;

    /**
     * @ORM\ManyToOne(targetEntity="Users")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     *
     * @OA\Property(type="integer", description="User associated with Sections", title="user id")
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
     * @return Tabs
     */
    public function getTab(): Tabs
    {
        return $this->tab;
    }

    /**
     * @param Tabs $tab
     */
    public function setTab(Tabs $tab): void
    {
        $this->tab = $tab;
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
            "tab" => $this->getTab()
        ];
    }
}