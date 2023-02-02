<?php

namespace App\Application\Controllers\User;

use App\Application\Controllers\User\UserControllerInterface;
use App\Infrastructure\Models\User\UserModelInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpNotFoundException;

class UserController implements UserControllerInterface
{
    private $model;

    public function __construct(UserModelInterface $model){
        $this->model = $model;
    }

    public function login(Request $request, Response $response, $args): Response
    {
        $params = $request->getParsedBody();

        $user = $this->model->findUserByUsernamePassword($params);
        if ($user){//TODO exception no user found
            $response->getBody()->write(json_encode($user));

            $response->withHeader("Content-Type","application/json")->withStatus(200);
        }else {
            $response->withStatus(404);
        }

        return $response;
    }

    public function logout(Request $request, Response $response, $args): Response{
        return $response;
    }

    public function register(Request $request, Response $response)
    {
        $params = $request->getParsedBody();

        $is_register_complete = $this->model->registerUser($params);

        $response->withStatus(200);
    }
}