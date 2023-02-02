<?php

namespace App\Application\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

interface ElementControllerInterface
{
public function getAll(Request $request, Response $response, $args);
public function getElement(Request $request, Response $response, $args);
public function editElement(Request $request, Response $response, $args);
public function deleteElement(Request $request, Response $response, $args);
public function createElement(Request $request, Response $response, $args);
}