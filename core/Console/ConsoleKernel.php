<?php

namespace Core\Console;

use Core\Console\Commands\MigrateCommand;
use Core\Exceptions\ContainerException;
use ReflectionException;

class ConsoleKernel
{
    protected array $commands = [
        MigrateCommand::class,
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

            if ($command->getSignature() === $commandName) {
                $command->handle();
                return;
            }
        }

        echo "Command not found.\n";
    }
}