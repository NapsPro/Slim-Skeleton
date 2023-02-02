<?php

namespace App\Application\Controllers\Task;

use App\Application\Controllers\Ticket\TicketControllerInterface;
use App\Infrastructure\Models\Tasks\TasksModelInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class TaskController implements TicketControllerInterface
{

    private $model;

    public function __construct(TasksModelInterface $model){
        $this->model = $model;
    }

    public function getAll(Request $request, Response $response, $args): Response
    {
        //Todo try catch
        $tasks = $this->model->getAll($args);
        $response->getBody()->write(json_encode($tasks));
        $response->withHeader("Content-Type","application/json")->withStatus(200);
        return $response;
    }

    public function getElement(Request $request, Response $response, $args): Response
    {
        $task= $this->model->getByID($request->getQueryParams());
        if ($task){
            $response->getBody()->write(json_encode($task));
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
        return ($this->model->delete_element($request->getQueryParams())) ?
            $response->withStatus(200): $response->withStatus(403);
    }

    public function createElement(Request $request, Response $response, $args): Response
    {
        return ($this->model->create_element($request->getParsedBody())) ?
            $response->withStatus(200): $response->withStatus(406);
    }

    public function distributor(Request $request, Response $response, $args): Response
    {
        switch ($request->getMethod()){
            case "GET":
                    if (array_key_exists("task", $request->getQueryParams())){
                        return $this->getElement($request, $response, $args);
                    }else{
                        return $this->getAll($request, $response, $args);
                    }

            case "POST":
                return $this->createElement($request, $response, $args);


            case "PUT":
                if (array_key_exists("task", $request->getQueryParams())){
                    return $this->editElement($request, $response, $args);
                }else{
                    return $response->withStatus(406);
                }

            case "DELETE":
                if (array_key_exists("task", $request->getQueryParams())){
                    return $this->editElement($request, $response, $args);
                }else{
                    return $response->withStatus(403);
                }
            default:
                return $response->withStatus(405);
        }

    }
}