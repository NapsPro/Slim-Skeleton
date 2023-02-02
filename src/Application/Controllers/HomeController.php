<?php

namespace App\Application\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class HomeController
{
    public function home(Request $request, Response $response, $args): Response
    {
        $response->getBody()->write('xD world!');
        return $response;
    }
}
