<?php

namespace App\Infrastructure\Repository\User;


use App\Application\Exceptions\UserException;
use App\Entities\Users;

interface UserRepositoryInterface
{
    /**
     * Search for user in the database
     *
     * @param array $params Array containing password and username
     * @return mixed
     *@throws UserException
     */
    public function findUserByUsernamePassword(array $params);

    /**
     * Register a user to the db
     *
     * @param array $params Array containing password email and username
     * @throws UserException
     * @return bool
     */
    public function registerUser($params): bool;
}