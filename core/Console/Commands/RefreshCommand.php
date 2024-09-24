<?php

namespace Core\Console\Commands;

use Core\Console\Command;

class RefreshCommand extends Command
{
    protected string $command = 'migrate:refresh';

    protected string $description = 'Rollback all migrations and run them again';

    public function handle(): void
    {
        echo "Rolling back all migrations...\n";
        $this->call('migrate:reset');

        echo "Running all migrations again...\n";
        $this->call('migrate');
    }
}