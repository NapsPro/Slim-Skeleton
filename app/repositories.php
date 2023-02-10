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
use App\Infrastructure\Repository\Database;
use App\Infrastructure\Repository\Sessions\DbSessionRepository;
use App\Infrastructure\Repository\Sessions\SessionRepositoryInterface;
use App\Infrastructure\Repository\Tasks\DbTasksRepository;
use App\Infrastructure\Repository\Sections\SectionRepositoryInterface;
use App\Infrastructure\Repository\Status\DbStatusRepository;
use App\Infrastructure\Repository\Status\StatusRepositoryInterface;
use App\Infrastructure\Repository\Tabs\DbTabRepository;
use App\Infrastructure\Repository\Tabs\TabsRepositoryInterface;
use App\Infrastructure\Repository\Tasks\TasksRepositoryInterface;
use App\Infrastructure\Repository\Tickets\DbTicketRepository;
use App\Infrastructure\Repository\Tickets\TicketRepositoryInterface;
use App\Infrastructure\Repository\User\UserRepository;
use App\Infrastructure\Repository\User\UserRepositoryInterface;
use App\Application\Controllers\User\UserControllerInterface;
use App\Application\Controllers\User\UserController;
use App\Infrastructure\Repository\Sections\DbSectionRepository;
use DI\ContainerBuilder;


return function (ContainerBuilder $containerBuilder) {
    // Here we map our UserRepository interface to its in memory implementation
    $containerBuilder->addDefinitions([
        Database::class => \DI\autowire(Database::class),
        UserRepositoryInterface::class => \DI\autowire(UserRepository::class),
        SectionRepositoryInterface::class => \DI\autowire(DbSectionRepository::class),
        StatusRepositoryInterface::class => \DI\autowire(DbStatusRepository::class),
        TabsRepositoryInterface::class => \DI\autowire(DbTabRepository::class),
        TasksRepositoryInterface::class => \DI\autowire(DbTasksRepository::class),
        TicketRepositoryInterface::class => \DI\autowire(DbTicketRepository::class),
        SessionRepositoryInterface::class=>\DI\autowire(DbSessionRepository::class),
        UserControllerInterface::class => \DI\autowire(UserController::class),
        SectionControllerInterface::class => \DI\autowire(SectionController::class),
        StatusControllerInterface::class => \DI\autowire(StatusController::class),
        TabControllerInterface::class => \DI\autowire(TabController::class),
        TaskControllerInterface::class => \DI\autowire(TaskController::class),
        TicketControllerInterface::class => \DI\autowire(TicketController::class),
        SessionControllerInterface::class=>\DI\autowire(SessionController::class),

    ]);
};


