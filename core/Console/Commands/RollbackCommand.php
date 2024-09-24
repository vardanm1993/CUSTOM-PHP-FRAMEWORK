<?php

namespace Core\Console\Commands;

use Core\App;
use Core\Console\Command;
use Core\Console\Traits\MigrationCommandTrait;
use Core\Database;
use Core\Exceptions\ContainerException;
use Core\Exceptions\Exception;
use Core\Migration\Migration;
use ReflectionException;

class RollbackCommand extends Command
{
    use MigrationCommandTrait;

    protected string $signature = 'migrate:rollback';
    protected string $description = 'Rollback the last database migration';

    /**
     * @throws ContainerException
     * @throws ReflectionException
     */
    public function handle()
    {
        $lastBatch = $this->getLastBatchNumber();

        if ($lastBatch === null) {
            echo "No migrations to rollback.\n";
            return;
        }

        $migrationsForRollback = array_reverse($this->getMigrationsByBatch($lastBatch));

        foreach ($migrationsForRollback as $migration) {
            $migrationFileName = $migration['migration'];
            $migrationClassName = $this->getMigrationClassName($migrationFileName);

            require_once dirname(__DIR__) . '/../../database/migrations/' . $migrationFileName . '.php';

            $instance = new $migrationClassName();

            if ($instance instanceof Migration) {
                try {
                    $instance->down();
                    $this->deleteMigrationRecord($migrationFileName);

                    echo "Rolled back: $migrationFileName\n";

                } catch (Exception $e) {
                    echo "Failed to rollback $migrationFileName: " . $e->getMessage() . "\n";
                }
            }
        }


    }

    /**
     * @throws ReflectionException
     * @throws ContainerException
     */
    private function getLastBatchNumber(): ?int
    {
        try {
            $sql = "SELECT MAX(batch) as batch FROM migrations";
            $result = App::resolve(Database::class)?->query($sql);

            return $result[0]['batch'] ?? null;
        } catch (Exception $e) {
            echo "Failed to get last batch number: " . $e->getMessage() . "\n";
            return null;
        }

    }

    /**
     * @throws ReflectionException
     * @throws ContainerException
     */
    private function getMigrationsByBatch(int $batch): ?array
    {
        try {
            $results = App::resolve(Database::class)
                ?->select(
                    table: 'migrations',
                    columns: 'migration',
                    conditions: [
                        'batch' => $batch
                    ]
                );

            return $results ?: [];

        } catch (Exception $e) {
            echo "Failed to get migrations by batch: " . $e->getMessage() . "\n";
            return [];
        }
    }
}