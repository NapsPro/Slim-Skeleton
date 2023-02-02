<?php

namespace App\Application\Controllers\Status;


use App\Infrastructure\Models\Status\StatusModelInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class StatusController implements StatusControllerInterface
{

    private $model;

    public function __construct(StatusModelInterface $model){
        $this->model = $model;
    }
    public function getAll(Request $request, Response $response, $args): Response
    {
        $status = $this->model->getAll($args);
        $response->getBody()->write(json_encode($status));
        $response->withHeader("Content-Type","application/json")->withStatus(200);
        return $response;
    }

    public function getElement(Request $request, Response $response, $args): Response
    {
        $status= $this->model->getByID($args);
        if ($status){
            $response->getBody()->write(json_encode($status));
            $response->withHeader("Content-Type","application/json")->withStatus(200);
        }else{
            $response->withStatus(404);
        }
        return $response;

    }

    public function editElement(Request $request, Response $response, $args): Response
    {
        return ($this->model->edit_element($request->getParsedBody())) ?
            $response->withStatus(200): $response->withStatus(406);
    }

    public function deleteElement(Request $request, Response $response, $args): Response
    {
        return ($this->model->delete_element($args)) ?
            $response->withStatus(200): $response->withStatus(403);
    }

    public function createElement(Request $request, Response $response, $args): Response
    {
        return ($this->model->create_element($request->getParsedBody())) ?
            $response->withStatus(200): $response->withStatus(403);
    }

}