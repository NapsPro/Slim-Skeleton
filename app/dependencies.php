<?php
declare(strict_types=1);

use App\Application\Settings\SettingsInterface;
use App\Infrastructure\Repository\Database;
use DI\ContainerBuilder;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\ORMSetup;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Monolog\Processor\UidProcessor;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;

return function (ContainerBuilder $containerBuilder) {
    $containerBuilder->addDefinitions([
        LoggerInterface::class => function (ContainerInterface $c) {
            $settings = $c->get(SettingsInterface::class);

            $loggerSettings = $settings->get('logger');
            $logger = new Logger($loggerSettings['name']);

            $processor = new UidProcessor();
            $logger->pushProcessor($processor);

            $handler = new StreamHandler($loggerSettings['path'], $loggerSettings['level']);
            $logger->pushHandler($handler);

            return $logger;
        },
        EntityManagerInterface::class => function(ContainerInterface $c){
            $settings = $c->get(SettingsInterface::class);
            $dbSettings = $settings["doctrine"]["connection"];
            $paths = $settings["metadata_dirs"];
            $config = ORMSetup::createAnnotationMetadataConfiguration($paths, $settings["doctrine"]["dev_mode"]);
            return EntityManager::create($dbSettings, $config);
        },
        DataBase::class => function(){
            return new Database();
    }
    ]);
};
