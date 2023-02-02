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
    public function getAll(Request $request, Response $response, $args)
    {

    }

    public function getElement(Request $request, Response $response, $args)
    {

    }

    public function editElement(Request $request, Response $response, $args)
    {
        // TODO: Implement editElement() method.
    }

    public function deleteElement(Request $request, Response $response, $args)
    {
        // TODO: Implement deleteElement() method.
    }

    public function createElement(Request $request, Response $response, $args)
    {
        // TODO: Implement createElement() method.
    }

}