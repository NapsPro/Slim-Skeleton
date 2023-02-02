<?php

namespace App\Application\Controllers\Ticket;



use App\Infrastructure\Models\Tickets\TicketModelInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class TicketController implements TicketControllerInterface

{
    private $model;

    public function __construct(TicketModelInterface $model){
        $this->model = $model;
    }
    public function getAll(Request $request, Response $response, $args): array
    {
        // TODO function getUserId() && isLogin(user) helper functions

      return $this->model->getAll($args); // TODO change To userLogin ID
    }

    public function getElement(Request $request, Response $response, $args): Response
    {
         $ticket= $this->model->getByID($args);
         if ($ticket){
             $response->getBody()->write(json_encode($ticket));
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