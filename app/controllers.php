<?php
declare(strict_types=1);

use App\Application\Controllers\Section\SectionController;
use App\Application\Controllers\Section\SectionControllerInterface;
use App\Application\Controllers\Sessions\SessionController;
use App\Application\Controllers\Sessions\SessionControllerInterface;
use App\Application\Controllers\Status\StatusController;
use App\Application\Controllers\Status\StatusControllerInterface;
use App\Application\Controllers\Tab\TabController;
use App\Application\Controllers\Tab\TabControllerInterface;
use App\Application\Controllers\Task\TaskController;
use App\Application\Controllers\Task\TaskControllerInterface;
use App\Application\Controllers\Ticket\TicketController;
use App\Application\Controllers\Ticket\TicketControllerInterface;
use App\Application\Controllers\User\UserControllerInterface;
use App\Application\Controllers\User\UserController;
use DI\ContainerBuilder;


return function (ContainerBuilder $containerBuilder) {
    // Here we map our Controller interface to its implementation
    $containerBuilder->addDefinitions([
        UserControllerInterface::class => \DI\autowire(UserController::class),
        SectionControllerInterface::class => \DI\autowire(SectionController::class),
        StatusControllerInterface::class => \DI\autowire(StatusController::class),
        TabControllerInterface::class => \DI\autowire(TabController::class),
        TaskControllerInterface::class => \DI\autowire(TaskController::class),
        TicketControllerInterface::class => \DI\autowire(TicketController::class),
        SessionControllerInterface::class=>\DI\autowire(SessionController::class),

    ]);
};