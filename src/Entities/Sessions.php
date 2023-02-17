<?php

namespace App\Entities;


use DateTime;

/**
 * @ORM\Entity
 * @ORM\Table(name="Sessions")
 *
 * @OA\Schema (
 *     description="Sessions Model",
 *     title="Sessions"
 * )
 */
class Sessions
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
     * @ORM\ManyToOne(targetEntity="Users")
     * @JoinColumn(name="user_id", referencedColumnName="id")
     *
     * @OA\Property(type="integer", description="User associated", title="User id")
     *
     * @var integer
     */
    private $user_id;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @OA\Property(type="string", description="Access Token", title="Access token")
     *
     * @var string
     */
    private $access_token;

    /**
     * @ORM\Column (type="datetime")
     *
     * @OA\Property(type="datetime", description="When access token expire", title="Expire AT")
     *
     * @var DateTime
     */
    private $access_token_expire;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @OA\Property(type="string", description="Refresh token", title="Refresh Token")
     *
     * @var string
     */
    private $refresh_token;

    /**
     * @ORM\Column (type="datetime")
     *
     * @OA\Property(type="satetime", description="When refresh token expire", title="Expire RT")
     *
     * @var DateTime
     */
    private $refresh_token_expire;

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

    /**
     * @return string
     */
    public function getAccessToken(): string
    {
        return $this->access_token;
    }

    /**
     * @param string $access_token
     */
    public function setAccessToken(string $access_token): void
    {
        $this->access_token = $access_token;
    }

    /**
     * @return DateTime
     */
    public function getAccessTokenExpire(): DateTime
    {
        return $this->access_token_expire;
    }

    /**
     * @param DateTime $access_token_expire
     */
    public function setAccessTokenExpire(DateTime $access_token_expire): void
    {
        $this->access_token_expire = $access_token_expire;
    }

    /**
     * @return string
     */
    public function getRefreshToken(): string
    {
        return $this->refresh_token;
    }

    /**
     * @param string $refresh_token
     */
    public function setRefreshToken(string $refresh_token): void
    {
        $this->refresh_token = $refresh_token;
    }

    /**
     * @return DateTime
     */
    public function getRefreshTokenExpire(): DateTime
    {
        return $this->refresh_token_expire;
    }

    /**
     * @param DateTime $refresh_token_expire
     */
    public function setRefreshTokenExpire(DateTime $refresh_token_expire): void
    {
        $this->refresh_token_expire = $refresh_token_expire;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

}