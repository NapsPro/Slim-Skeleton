<?php

namespace App\Infrastructure;

use Firebase\JWT\JWT;

class TokenFactory

{
    private static $key = "supersecretkeyyoushouldnotcommittogithub";

    /**
     * @param integer $expire_sec the seconds for the token last
     * @return array with token and expire time in seconds
     */
    public static function createToke($expire_sec): array
    {

    $iat = time();
    $exp = $iat + $expire_sec;
    $payload = [
        //"iss" =>"https://sandbox.exads.rocks", //issuer
        //"aud" =>"https://sandbox.exads.rocks", //audience
        "iat" => $iat, //Time when it was issued
        "exp" => $exp // Time when it expires
        ];

    $jwt = JWT::encode($payload, self::$key , "HS256");
    return [
        "token" => $jwt,
        "expires" => $exp
    ];
    }
}