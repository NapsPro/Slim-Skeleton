<?php
declare(strict_types=1);

use App\Application\Controllers\Sessions\SessionControllerInterface;
use App\Application\Middleware\CORSMiddleware;
use App\Application\Middleware\SessionMiddleware;
use Slim\App;

return function (App $app) {
    $app->add(SessionMiddleware::class);
    $app->add(CORSMiddleware::class);
    $app->add(new Tuupola\Middleware\JwtAuthentication([
        "secret" => $_ENV["JWT_SECRET"],
        "ignore" => ["/users/login", "/users/register"],
    ], $app->getContainer()->get(SessionControllerInterface::class)));
};
