<?php
declare(strict_types=1);

use App\Infrastructure\Repository\Sessions\PdoSessionRepository;
use App\Infrastructure\Repository\Sessions\SessionRepositoryInterface;
use App\Infrastructure\Repository\Tasks\PdoTasksRepository;
use App\Infrastructure\Repository\Sections\SectionRepositoryInterface;
use App\Infrastructure\Repository\Status\PdoStatusRepository;
use App\Infrastructure\Repository\Status\StatusRepositoryInterface;
use App\Infrastructure\Repository\Tabs\PdoTabRepository;
use App\Infrastructure\Repository\Tabs\TabsRepositoryInterface;
use App\Infrastructure\Repository\Tasks\TasksRepositoryInterface;
use App\Infrastructure\Repository\Tickets\PdoTicketRepository;
use App\Infrastructure\Repository\Tickets\TicketRepositoryInterface;
use App\Infrastructure\Repository\User\PdoUserRepository;
use App\Infrastructure\Repository\User\UserRepositoryInterface;
use App\Infrastructure\Repository\Sections\PdoSectionRepository;
use DI\ContainerBuilder;


return function (ContainerBuilder $containerBuilder) {
    // Here we map our Repository interface to its implementation
    $containerBuilder->addDefinitions([
        UserRepositoryInterface::class => \DI\autowire(PdoUserRepository::class),
        SectionRepositoryInterface::class => \DI\autowire(PdoSectionRepository::class),
        StatusRepositoryInterface::class => \DI\autowire(PdoStatusRepository::class),
        TabsRepositoryInterface::class => \DI\autowire(PdoTabRepository::class),
        TasksRepositoryInterface::class => \DI\autowire(PdoTasksRepository::class),
        TicketRepositoryInterface::class => \DI\autowire(PdoTicketRepository::class),
        SessionRepositoryInterface::class=>\DI\autowire(PdoSessionRepository::class),
    ]);
};


