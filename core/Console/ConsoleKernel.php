<?php

namespace Core\Console;

use Core\Console\Commands\MakeMigrationCommand;
use Core\Console\Commands\MigrateCommand;
use Core\Console\Commands\RollbackCommand;
use Core\Exceptions\ContainerException;
use ReflectionException;

class ConsoleKernel
{
    protected array $commands = [
        MigrateCommand::class,
        RollbackCommand::class,
        MakeMigrationCommand::class
    ];

    /**
     * @throws ReflectionException
     * @throws ContainerException
     */
    public function handle(array $argv): void
    {
        $commandName = $argv[1] ?? null;

        foreach ($this->commands as $commandClass) {
            $command = new $commandClass();

            $commandSignature = explode(' ', $command->getSignature())[0];

            if ($commandSignature === $commandName) {
                $command->handle();
                return;
            }
        }

        echo "Command not found.\n";
    }
}