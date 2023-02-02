<?php

namespace App\Application\Controllers\Section;

use App\Infrastructure\Models\Sections\SectionModelInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class SectionController implements SectionControllerInterface
{
    private $model;

    public function __construct(SectionModelInterface $model){
        $this->model = $model;
    }

    public function getAll(Request $request, Response $response, $args): Response
    {
        $sections = $this->model->getAll($args);
        $response->getBody()->write(json_encode($sections));
        $response->withHeader("Content-Type","application/json")->withStatus(200);
        return $response;
    }

    public function getElement(Request $request, Response $response, $args): Response
    {
        $section= $this->model->getByID($args);
        if ($section){
            $response->getBody()->write(json_encode($section));
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