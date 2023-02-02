<?php
declare(strict_types=1);

use App\Application\Controllers\Section\SectionController;
use App\Application\Controllers\Section\SectionControllerInterface;
use App\Application\Controllers\Status\StatusController;
use App\Application\Controllers\Status\StatusControllerInterface;
use App\Application\Controllers\Tab\TabController;
use App\Application\Controllers\Tab\TabControllerInterface;
use App\Application\Controllers\Task\TaskController;
use App\Application\Controllers\Task\TaskControllerInterface;
use App\Application\Controllers\Ticket\TicketController;
use App\Application\Controllers\Ticket\TicketControllerInterface;
use App\Infrastructure\Models\Database;
use App\Infrastructure\Models\Tasks\DbTasksModel;
use App\Infrastructure\Models\Sections\SectionModelInterface;
use App\Infrastructure\Models\Status\DbStatusModel;
use App\Infrastructure\Models\Status\StatusModelInterface;
use App\Infrastructure\Models\Tabs\DbTabModel;
use App\Infrastructure\Models\Tabs\TabsModelInterface;
use App\Infrastructure\Models\Tasks\TasksModelInterface;
use App\Infrastructure\Models\Tickets\DbTicketModel;
use App\Infrastructure\Models\Tickets\TicketModelInterface;
use App\Infrastructure\Models\User\DbUserModel;
use App\Infrastructure\Models\User\UserModelInterface;
use App\Application\Controllers\User\UserControllerInterface;
use App\Application\Controllers\User\UserController;
use App\Infrastructure\Models\Sections\DbSectionModel;
use DI\ContainerBuilder;


return function (ContainerBuilder $containerBuilder) {
    // Here we map our UserRepository interface to its in memory implementation
    $containerBuilder->addDefinitions([
        Database::class => \DI\autowire(Database::class),
        UserModelInterface::class => \DI\autowire(DbUserModel::class),
        SectionModelInterface::class => \DI\autowire(DbSectionModel::class),
        StatusModelInterface::class => \DI\autowire(DbStatusModel::class),
        TabsModelInterface::class => \DI\autowire(DbTabModel::class),
        TasksModelInterface::class => \DI\autowire(DbTasksModel::class),
        TicketModelInterface::class => \DI\autowire(DbTicketModel::class),
        UserControllerInterface::class => \DI\autowire(UserController::class),
        SectionControllerInterface::class => \DI\autowire(SectionController::class),
        StatusControllerInterface::class => \DI\autowire(StatusController::class),
        TabControllerInterface::class => \DI\autowire(TabController::class),
        TaskControllerInterface::class => \DI\autowire(TaskController::class),
        TicketControllerInterface::class => \DI\autowire(TicketController::class),

    ]);
};


