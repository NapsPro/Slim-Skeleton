<?php

namespace App\Application\Controllers\User;

use App\Infrastructure\Models\User\UserModelInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

interface UserControllerInterface
{

    public function login(Request $request, Response $response, $args);

    public function logout(Request $request, Response $response, $args);

    public function register(Request $request, Response $response);
}