<?php

namespace App\Application\Controllers\Sessions;

use App\Infrastructure\Repository\Sessions\SessionRepositoryInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class SessionController implements SessionControllerInterface
{
    private $model;

    public function __construct(SessionRepositoryInterface $model){
        $this->model = $model;
    }


    public function getSession($access_token)
    {
       $this->model->getSession($access_token);
    }

    public function updateSession($params)
    {
        return $this->model->updateSession($params);
    }

    public function deleteSession($params)
    {
        $this->model->deleteSession($params);
    }

    public function createSession($params)
    {
        return $this->model->createSession($params);
    }
}