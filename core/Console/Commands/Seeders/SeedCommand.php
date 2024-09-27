<?php

namespace Core\Console\Commands\Seeders;

use Core\Console\Command;
use Seeders\DatabaseSeeder;

class SeedCommand extends Command
{
    protected string $signature = 'db:seed';

    protected string $description = 'Seed to the database';

    public function handle(): void
    {
        try {
            $seeder = new DatabaseSeeder();
            $seeder->run();

            echo "Database seeded successfully.\n";
        } catch (\Throwable $e) {
            echo "Error seeding the database: " . $e->getMessage() . "\n";
        }
    }
}