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


    public function getSession($params)
    {
       $this->model->getByID($params);
    }

    public function updateSession($params)
    {
        return $this->model->edit_element($params);
    }

    public function deleteSession($params)
    {
        $this->model->delete_element($params);
    }

    public function createSession($params)
    {
        return $this->model->create_element($params);
    }
}