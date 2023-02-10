<?php

namespace App\Application\Controllers\Task;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

interface TaskControllerInterface
{
    public function distributor(Request $request, Response $response, $args): Response;
}