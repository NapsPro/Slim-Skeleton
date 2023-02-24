<?php

namespace App\Entities;


use App\Application\Exceptions\SessionException;
use DateTime;
use DateTimeZone;
use Exception;
use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;
use OpenApi\Annotations as OA;
require_once(__DIR__.'/../helpers/SecondsToDatetime.php');

/**
 * @ORM\Entity
 * @ORM\Table(name="Sessions")
 *
 * @OA\Schema (
 *     description="Sessions Model",
 *     title="Sessions"
 * )
 */
class Sessions implements JsonSerializable
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
     * @ORM\JoinColumn (name="user_id", referencedColumnName = "id")
     *
     * @OA\Property(type="integer", description="User associated", title="User id")
     *
     * @var Users
     */
    private $user;

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
     * @OA\Property(type="datetime", description="When refresh token expire", title="Expire RT")
     *
     * @var DateTime
     */
    private $refresh_token_expire;

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
     * @param int $access_token_expire
     * @throws Exception
     */
    public function setAccessTokenExpire(int $access_token_expire): void
    {
        try {
            $this->access_token_expire = secondsToDatetime($access_token_expire);
        } catch (Exception $e) {
            throw new SessionException($e->getMessage(), 400);
        }
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
     * @param int $refresh_token_expire
     * @throws Exception
     */
    public function setRefreshTokenExpire(int $refresh_token_expire): void
    {
        try {
            $this->refresh_token_expire = secondsToDatetime($refresh_token_expire);
        }catch (Exception $e){
            throw new SessionException($e->getMessage(),400);
    }

    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        return [
            'id' => $this->getId(),
            'access_token' => $this->getAccessToken(),
            'refresh_token' => $this->getRefreshToken(),
            'access_token_expire' => $this->getAccessTokenExpire(),
            'refresh_token_expire' => $this->getRefreshTokenExpire()
        ];
    }
}