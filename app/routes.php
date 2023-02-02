<?php

declare(strict_types=1);

use App\Application\Controllers\HomeController;
use App\Application\Controllers\Section\SectionControllerInterface;
use App\Application\Controllers\Status\StatusControllerInterface;
use App\Application\Controllers\Tab\TabControllerInterface;
use App\Application\Controllers\Task\TaskControllerInterface;
use App\Application\Controllers\Ticket\TicketControllerInterface;
use App\Application\Controllers\User\UserControllerInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;

return function (App $app) {
    $app->options('/{routes:.*}', function (Request $request, Response $response) {
        // CORS Pre-Flight OPTIONS Request Handler
        return $response;
    });

    $app->get('/', HomeController::class . ":home");

    // --------------------- USERS ------------------------
    $app->group('/users', function (Group $group) {
        $group->post('/login', UserControllerInterface::class.":login");
        $group->get("/logout", UserControllerInterface::class.":logout");
        $group->post("/register",UserControllerInterface::class.":register");
    });

    //-------------------- Tickets -----------------------
    $app->group("/tickets", function (Group $group){
        $group->get("",TicketControllerInterface::class.":getAll");
        $group->get("/{ticket_slug}", TicketControllerInterface::class.":getElement");
        $group->put("/{ticket_slug}", TicketControllerInterface::class.":editElement");
        $group->post("/create", TicketControllerInterface::class.":createElement");
        $group->delete("/{id}", TicketControllerInterface::class.":deleteElement");
    });
    //-------------------- Tabs -----------------------
    $app->group("/{ticket_slug}/tabs", function (Group $group){
        $group->get("",TabControllerInterface::class.":getAll");
        $group->get("/{id}", TabControllerInterface::class.":getElement");
        $group->put("/{id}", TabControllerInterface::class.":editElement");
        $group->post("/create", TabControllerInterface::class.":createElement");
        $group->delete("/{id}", TabControllerInterface::class.":deleteElement");
    });
    //---------------- Section -----------------
    $app->group("/{tab_id}/sections", function (Group $group){
        $group->get("", SectionControllerInterface::class.":getAll");
        $group->get("/{id}", SectionControllerInterface::class.":getElement");
        $group->put("/{id}", SectionControllerInterface::class.":editElement");
        $group->post("/create", SectionControllerInterface::class.":createElement");
        $group->delete("/{id}", SectionControllerInterface::class.":deleteElement");
    });
    //------------------- Tasks ------------------
    $app->group("/{section_id}", function (Group $group){
        $group->map(["GET","POST","PUT","DELETE"],"",TaskControllerInterface::class."distributor");
    });
    //------------------ Status ------------------
    $app->group("/status", function (Group $group) {
        $group->get("", StatusControllerInterface::class.":getAll");
        $group->get("/{id}", StatusControllerInterface::class.":getElement");
        $group->put("/{id}", StatusControllerInterface::class.":editElement");
        $group->post("/create", StatusControllerInterface::class.":createElement");
        $group->delete("/{id}", StatusControllerInterface::class.":deleteElement");
    });
};