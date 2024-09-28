<?php

namespace Core\Console\Commands\Migrations;

use Core\Console\Command;
use Core\Console\Traits\Migrations\MigrationHasSpecialPropsTrait;
use Core\Exceptions\ContainerException;
use ReflectionException;

class RefreshCommand extends Command
{
    use MigrationHasSpecialPropsTrait;

    protected string $command = 'migrate:refresh {--seed}';

    protected string $description = 'Rollback all migrations and run them again';

    /**
     * @throws ReflectionException
     * @throws ContainerException
     */
    public function handle(): void
    {
        echo "Rolling back all migrations...\n";
        $this->call('migrate:reset');

        echo "Running all migrations again...\n";
        $this->call('migrate');

        $this->hasSeed();
    }
}