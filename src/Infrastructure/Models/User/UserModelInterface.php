<?php

namespace App\Infrastructure\Models\User;

interface UserModelInterface
{
    public function findUserByUsernamePassword($params);

    public function registerUser($params);
}