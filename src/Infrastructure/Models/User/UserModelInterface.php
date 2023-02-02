<?php

namespace App\Infrastructure\Models\User;

use App\Domain\CrudOp;
use App\Infrastructure\Models\Database;

interface UserModelInterface
{
    public function findUserByUsernamePassword($params);

    public function registerUser($params);
}