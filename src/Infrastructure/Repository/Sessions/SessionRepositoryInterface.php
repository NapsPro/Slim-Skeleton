<?php

namespace App\Infrastructure\Repository\Sessions;

use App\Infrastructure\Repository\CrudOperation;

interface SessionRepositoryInterface
{
    public function getByAllFields($params);

    public function getSession($access_token);

    public function createSession($params);

    public function updateSession($params);

    public function deleteSession($params);
}