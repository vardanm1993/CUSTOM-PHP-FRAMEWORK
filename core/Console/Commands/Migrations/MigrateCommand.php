<?php

namespace Core\Console\Commands\Migrations;

use Core\App;
use Core\Console\Command;
use Core\Console\Traits\MigrationCommandTrait;
use Core\Database;
use Core\Exceptions\ContainerException;
use Core\Exceptions\Exception;
use Core\Migration\Migration;
use ReflectionException;

class MigrateCommand extends Command
{
    use MigrationCommandTrait;
    protected string $signature = 'migrate';
    protected string $description = 'Run the database migrations';

    /**
     * @throws ContainerException
     * @throws ReflectionException
     */
    public function handle(): void
    {
        if (!$this->migrationsTableExists()) {
            $this->createMigrationsTable();
        }

        $path = dirname(__DIR__) . '/../../../database/migrations';
        $migrationFiles = glob("{$path}/*.php");
        $batchNumber = $this->getNextBatchNumber();

        $migratedMigrations = $this->getMigratedMigrations();

        foreach ($migrationFiles as $file) {
            $migrationFileName = $this->getMigrationFileName($file);
            $migrationClassName = $this->getMigrationClassName(migrationFileName: $migrationFileName);

            if (!in_array($migrationFileName, $migratedMigrations, true)) {

                require_once $file;

                $instance = new $migrationClassName();

                if ($instance instanceof Migration) {
                    try {
                        $instance->up();
                        $this->insertMigration($migrationFileName, $batchNumber);

                        echo "Migrated: $migrationFileName\n";

                    }  catch (\Exception $e) {
                        echo "Failed to migrate $migrationFileName: " . $e->getMessage() . "\n";
                    }
                }
            }

        }
    }

    /**
     * @throws ContainerException
     * @throws ReflectionException
     */
    private function insertMigration(string $migrationFileName, int $batchNumber): void
    {
        try {
            App::resolve(Database::class)?->insert('migrations',
                [
                    'migration' => $migrationFileName,
                    'batch' => $batchNumber
                ]
            );
        } catch (\Exception $e) {
            echo "Failed to insert migration record for $migrationFileName: " . $e->getMessage() . "\n";
        }
    }

    /**
     * @throws ReflectionException
     * @throws ContainerException
     */
    private function getNextBatchNumber(): int
    {
        try {
            $sql = "SELECT MAX(batch) as batch FROM migrations";
            $result = App::resolve(Database::class)?->query($sql);

            return $result ? $result[0]['batch'] + 1 : 1;

        } catch (\Exception $e) {
            echo "Failed to get next batch number: " . $e->getMessage() . "\n";
            return 1;
        }

    }

    private function getMigrationFileName(string $file): string
    {
        return pathinfo($file, PATHINFO_FILENAME);
    }

    /**
     * @throws ContainerException
     * @throws ReflectionException
     */
    private function getMigratedMigrations(): array
    {

        try {
            $migrations = [];

            $sql = "SELECT migration FROM migrations";

            $results = App::resolve(Database::class)?->query($sql);

            if ($results) {
                foreach ($results as $result) {
                    $migrations[] = $result['migration'];
                }
            }

            return $migrations;

        } catch (Exception $e) {
            echo "Failed to fetch migrated migrations: " . $e->getMessage() . "\n";
            return [];
        }
    }

    /**
     * @throws ContainerException
     * @throws ReflectionException
     */
    private function migrationsTableExists(): bool
    {
        try {
            $sql = "SHOW TABLES LIKE 'migrations'";
            $result = App::resolve(Database::class)?->query($sql);

            return !empty($result);
        } catch (Exception $e) {
            echo "Failed to check if migrations table exists: " . $e->getMessage() . "\n";
            return false;
        }
    }

    /**
     * @throws ContainerException
     * @throws ReflectionException
     */
    private function createMigrationsTable(): void
    {

        $sql = "
            CREATE TABLE  migrations (
                id INT AUTO_INCREMENT PRIMARY KEY,
                migration VARCHAR(255) NOT NULL,
                batch INT NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            )
        ";

        try {
            App::resolve(Database::class)?->query($sql);
            echo "Migrations table created.\n";
        } catch (\Exception $e) {
            echo "Failed to create migrations table: " . $e->getMessage() . "\n";
        }
    }
}