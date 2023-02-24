<?php

namespace App\Infrastructure\Repository\User;


interface UserRepositoryInterface
{

    public function findUserByUsernamePassword(array $params);


    public function registerUser($params);
}