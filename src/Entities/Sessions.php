<?php

namespace App\Entities;


use DateTime;

/**
 * @ORM\Entity
 * @ORM\Table(name="Sessions")
 */
class Sessions
{

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @var int
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Users")
     * @JoinColumn(name="user_id", referencedColumnName="id")
     * @var integer
     */
    private $user_id;

    /**
     * @ORM\Column(type="string", length=255)
     * @var string
     */
    private $access_token;

    /**
     * @ORM\Column (type="datetime")
     * @var DateTime
     */
    private $access_token_expire;

    /**
     * @ORM\Column(type="string", length=255)
     * @var string
     */
    private $refresh_token;

    /**
     * @ORM\Column (type="datetime")
     * @var DateTime
     */
    private $refresh_token_expire;
}