<?php

namespace App\Infrastructure\Repository\User;


interface UserRepositoryInterface
{
    public function findUserByUsernamePassword($params);

    public function registerUser($params);
}