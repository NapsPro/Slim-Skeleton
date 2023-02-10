<?php

namespace App\Application\Controllers\Sessions;

use App\Application\Controllers\ElementControllerInterface;

interface SessionControllerInterface
{
public function getSession($params);

public function createSession($params);

public function updateSession($params);

public function deleteSession($params);
}