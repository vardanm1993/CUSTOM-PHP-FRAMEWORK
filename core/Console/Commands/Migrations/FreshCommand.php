<?php

namespace Core\Console\Commands\Migrations;

use Core\App;
use Core\Console\Command;
use Core\Console\Traits\Migrations\MigrationHasSpecialPropsTrait;
use Core\Database;
use Core\Exceptions\ContainerException;
use ReflectionException;

class FreshCommand extends Command
{
    use MigrationHasSpecialPropsTrait;

    protected string $signature = 'migrate:fresh {seed}';
    protected string $description = 'Drop all tables and re-run all migrations';

    /**
     * @throws ContainerException
     * @throws ReflectionException
     */
    public function handle(): void
    {
        echo "Dropping all tables...\n";
        $this->dropAllTables();

        echo "Running all migrations...\n";
        $this->call('migrate');

        $this->hasSeed();
    }

    /**
     * @throws ReflectionException
     * @throws ContainerException
     */
    private function dropAllTables(): void
    {
        $db = App::resolve(Database::class);
        $tables = $db->query("SHOW TABLES");

        if ($tables) {
            foreach ($tables as $table) {

                $tableName = $table[array_key_first($table)];
                echo "Dropping table: $tableName\n";
                $db->query("DROP TABLE IF EXISTS `$tableName`");
            }
        } else {
            echo "No tables found in the database.\n";
        }
    }


}