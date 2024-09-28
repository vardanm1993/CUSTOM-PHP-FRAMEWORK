<?php

namespace Core\Console\Commands\Migrations;

use Core\App;
use Core\Console\Command;
use Core\Console\Traits\Migrations\MigrationCommandTrait;
use Core\Database;
use Core\Exceptions\ContainerException;
use Core\Exceptions\Exception;
use Core\Migration\Migration;
use ReflectionException;

class ResetCommand extends Command
{
    use MigrationCommandTrait;

    protected string $signature = 'migrate:reset';

    protected string $description = 'Rollback all database migrations';

    /**
     * @throws ReflectionException
     * @throws ContainerException
     */
    public function handle(): void
    {
        $migrations = array_reverse($this->getAllMigrations());

        if (empty($migrations)) {
            echo "No migrations to reset.\n";
            return;
        }

        foreach ($migrations as $migration) {
            $migrationFileName = $migration['migration'];
            $migrationClassName = $this->getMigrationClassName($migrationFileName);

            require_once dirname(__DIR__,4) . '/database/migrations/' . $migrationFileName . '.php';

            $instance = new $migrationClassName();

            if ($instance instanceof Migration) {
                try {
                    $instance->down();
                    $this->deleteMigrationRecord($migrationFileName);
                    echo "Rolled back: $migrationFileName\n";
                } catch (ReflectionException $e) {
                    echo "Failed to rollback $migrationFileName: " . $e->getMessage() . "\n";
                }
            }
        }
    }

    /**
     * @throws ReflectionException
     * @throws ContainerException
     */
    private function getAllMigrations(): array
    {
        try {
            $sql = "SELECT * FROM migrations ORDER BY batch DESC, id DESC";
            return App::resolve(Database::class)?->query($sql) ?? [];
        } catch (Exception $e) {
            echo "Failed to fetch all migrations: " . $e->getMessage() . "\n";
            return [];
        }
    }
}