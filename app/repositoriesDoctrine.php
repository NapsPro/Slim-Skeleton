<?php
declare(strict_types=1);

use App\Infrastructure\Repository\Sessions\DocSessionRepository;
use App\Infrastructure\Repository\Sessions\SessionRepositoryInterface;
use App\Infrastructure\Repository\Tasks\DocTasksRepository;
use App\Infrastructure\Repository\Sections\SectionRepositoryInterface;
use App\Infrastructure\Repository\Status\DocStatusRepository;
use App\Infrastructure\Repository\Status\StatusRepositoryInterface;
use App\Infrastructure\Repository\Tabs\DocTabRepository;
use App\Infrastructure\Repository\Tabs\TabsRepositoryInterface;
use App\Infrastructure\Repository\Tasks\TasksRepositoryInterface;
use App\Infrastructure\Repository\Tickets\DocTicketsRepository;
use App\Infrastructure\Repository\Tickets\TicketRepositoryInterface;
use App\Infrastructure\Repository\User\DocUserRepository;
use App\Infrastructure\Repository\User\UserRepositoryInterface;
use App\Infrastructure\Repository\Sections\DocSectionRepository;
use DI\ContainerBuilder;


return function (ContainerBuilder $containerBuilder) {
    // Here we map our Repository interface to its implementation
    $containerBuilder->addDefinitions([
        UserRepositoryInterface::class => \DI\autowire(DocUserRepository::class),
        SectionRepositoryInterface::class => \DI\autowire(DocSectionRepository::class),
        StatusRepositoryInterface::class => \DI\autowire(DocStatusRepository::class),
        TabsRepositoryInterface::class => \DI\autowire(DocTabRepository::class),
        TasksRepositoryInterface::class => \DI\autowire(DocTasksRepository::class),
        TicketRepositoryInterface::class => \DI\autowire(DocTicketsRepository::class),
        SessionRepositoryInterface::class=>\DI\autowire(DocSessionRepository::class),
    ]);
};