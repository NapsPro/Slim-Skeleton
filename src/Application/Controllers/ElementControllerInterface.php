<?php

namespace App\Application\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * @OA\Server(url="https://sandbox.exads.rocks")
 * @OA\Info(title="Jira-Checklist", version="0.1")
 * @OA\SecurityScheme(
 *     type="http",
 *     description="use /users/login to get the JWT Token",
 *     name="Authorization",
 *     in="header",
 *     scheme="bearer",
 *     bearerFormat="JWT",
 *     securityScheme="bearerAuth",
 * )
 */
interface ElementControllerInterface
{
public function getAll(Request $request, Response $response, $args);
public function getElement(Request $request, Response $response, $args);
public function editElement(Request $request, Response $response, $args);
public function deleteElement(Request $request, Response $response, $args);
public function createElement(Request $request, Response $response, $args);
}