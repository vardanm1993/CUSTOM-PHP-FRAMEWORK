<?php

namespace Core\Console;

use Core\Console\Commands\Controller\MakeControllerCommand;
use Core\Console\Commands\Factory\MakeFactoryCommand;
use Core\Console\Commands\Migrations\FreshCommand;
use Core\Console\Commands\Migrations\MakeMigrationCommand;
use Core\Console\Commands\Migrations\MigrateCommand;
use Core\Console\Commands\Migrations\RefreshCommand;
use Core\Console\Commands\Migrations\ResetCommand;
use Core\Console\Commands\Migrations\RollbackCommand;
use Core\Console\Commands\Model\MakeModelCommand;
use Core\Console\Commands\Seeders\MakeSeederCommand;
use Core\Console\Commands\Seeders\SeedCommand;
use Core\Exceptions\ContainerException;
use ReflectionException;

class ConsoleKernel
{
    protected static array $commands = [
        'migrate' => MigrateCommand::class,
        'make:migration' => MakeMigrationCommand::class,
        'migrate:rollback' => RollbackCommand::class,
        'migrate:reset' => ResetCommand::class,
        'migrate:refresh' => RefreshCommand::class,
        'migrate:fresh' => FreshCommand::class,
        'db:seed' => SeedCommand::class,
        'make:factory' => MakeFactoryCommand::class,
        'make:seeder' => MakeSeederCommand::class,
        'make:controller' => MakeControllerCommand::class,
        'make:model' => MakeModelCommand::class,
    ];

    /**
     * @throws ReflectionException
     * @throws ContainerException
     */
    public function handle(array $argv): void
    {
        $commandName = $argv[1] ?? null;

        if (!$commandName || !isset(self::$commands[$commandName])) {
            echo "Command not found.\n";
            return;
        }

        $commandClass = self::$commands[$commandName];

        $command = new $commandClass();
        $command->handle();
    }

    public static function getCommands(): array
    {
        return self::$commands;
    }
}