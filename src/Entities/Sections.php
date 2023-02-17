<?php

namespace App\Entities;


/**
 * @ORM\Entity
 * @ORM\Table(name="Sections")
 * @OA\Schema (
 *     description = "Section Model",
 *     title="Sections"
 * )
 */
class Sections
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
     * @ORM\ManyToOne(targetEntity="Users")
     * @JoinColumn(name="tab_id", referencedColumnName="id")
     *
     * @OA\Property(type="integer", description="Tab associated", title="Tab id")
     *
     * @var integer
     */
    private $tab_id;

    /**
     * @ORM\ManyToOne(targetEntity="Users")
     * @JoinColumn(name="user_id", referencedColumnName="id")
     *
     * @OA\Property(type="integer", description="User associated with Sections", title="user id")
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
     * @return int
     */
    public function getTabId(): int
    {
        return $this->tab_id;
    }

    /**
     * @param int $tab_id
     */
    public function setTabId(int $tab_id): void
    {
        $this->tab_id = $tab_id;
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