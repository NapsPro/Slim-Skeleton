<?php

declare(strict_types=1);

use App\Application\Controllers\HomeController;
use App\Application\Controllers\Section\SectionControllerInterface;
use App\Application\Controllers\Sessions\SessionControllerInterface;
use App\Application\Controllers\Status\StatusControllerInterface;
use App\Application\Controllers\Tab\TabControllerInterface;
use App\Application\Controllers\Task\TaskControllerInterface;
use App\Application\Controllers\Ticket\TicketControllerInterface;
use App\Application\Controllers\User\UserControllerInterface;
use App\Application\Controllers\Sessions;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;

return function (App $app) {

    $app->get('/', HomeController::class . ":home");

    // --------------------- USERS ------------------------
    $app->group('/users', function (Group $group) {
        $group->post('/login', UserControllerInterface::class.":login");
        $group->get("/logout", UserControllerInterface::class.":logout");
        $group->post("/register",UserControllerInterface::class.":register");
        $group->post("/refresh", UserControllerInterface::class.":updateSession");
    });
    //-------------------- Tickets -----------------------
    $app->group("/tickets", function (Group $group){
        $group->get("",TicketControllerInterface::class.":getAll");
        $group->get("/{ticket_slug}", TicketControllerInterface::class.":getElement");
        $group->put("/{ticket_slug}", TicketControllerInterface::class.":editElement");
        $group->post("/create", TicketControllerInterface::class.":createElement");
        $group->delete("/{ticket_slug}", TicketControllerInterface::class.":deleteElement");
    });
    //-------------------- Tabs -----------------------
    $app->group("/{ticket_slug}/tabs", function (Group $group){
        $group->get("",TabControllerInterface::class.":getAll");
        $group->get("/{id:[0-9]+}", TabControllerInterface::class.":getElement");
        $group->put("/{id:[0-9]+}", TabControllerInterface::class.":editElement");
        $group->post("/create", TabControllerInterface::class.":createElement");
        $group->delete("/{id:[0-9]+}", TabControllerInterface::class.":deleteElement");
    });
    //---------------- Section -----------------
    $app->group("/{tab_id:[0-9]+}/sections", function (Group $group){
        $group->get("", SectionControllerInterface::class.":getAll");
        $group->get("/{id:[0-9]+}", SectionControllerInterface::class.":getElement");
        $group->put("/{id:[0-9]+}", SectionControllerInterface::class.":editElement");
        $group->post("/create", SectionControllerInterface::class.":createElement");
        $group->delete("/{id:[0-9]+}", SectionControllerInterface::class.":deleteElement");
    });
    //------------------- Tasks ------------------
    $app->group("/{section_id:[0-9]+}/task", function (Group $group){
        $group->get("", TaskControllerInterface::class.":getAll");
        $group->get("/{id:[0-9]+}", TaskControllerInterface::class.":getElement");
        $group->put("/{id:[0-9]+}", TaskControllerInterface::class.":editElement");
        $group->post("/create", TaskControllerInterface::class.":createElement");
        $group->delete("/{id:[0-9]+}", TaskControllerInterface::class.":deleteElement");
    });
    //------------------ Status ------------------
    $app->group("/status", function (Group $group) {
        $group->get("", StatusControllerInterface::class.":getAll");
        $group->get("/{id:[0-9]+}", StatusControllerInterface::class.":getElement");
        $group->put("/{id:[0-9]+}", StatusControllerInterface::class.":editElement");
        $group->post("/create", StatusControllerInterface::class.":createElement");
        $group->delete("/{id:[0-9]+}", StatusControllerInterface::class.":deleteElement");
    });

};