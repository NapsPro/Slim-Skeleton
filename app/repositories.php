<?php
declare(strict_types=1);

use App\Infrastructure\Models\Database;
use App\Infrastructure\Models\User\DbUserModel;
use App\Infrastructure\Models\User\UserModelInterface;
use App\Application\Controllers\User\UserControllerInterface;
use App\Application\Controllers\User\UserController;
use DI\ContainerBuilder;


return function (ContainerBuilder $containerBuilder) {
    // Here we map our UserRepository interface to its in memory implementation
    $containerBuilder->addDefinitions([
        Database::class => \DI\autowire(Database::class),
        UserModelInterface::class => \DI\autowire(DbUserModel::class),
        UserControllerInterface::class => \DI\autowire(UserController::class),

    ]);
};


