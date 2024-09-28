<?php

namespace Core\Console\Traits\Migrations;

use Core\App;
use Core\Database;
use Core\Exceptions\Exception;
use ReflectionException;

trait MigrationCommandTrait
{
    /**
     * @param string $migrationFileName
     * @param bool $onlyClass
     * @return string
     */
    private function getMigrationClassName(string $migrationFileName, bool $onlyClass = false): string
    {
        $withoutTimestamp = preg_replace('/^\d{4}_\d{2}_\d{2}_\d+_/', '', $migrationFileName);

        if ($onlyClass) {
            return str_replace(' ', '', ucwords(str_replace('_', ' ', $withoutTimestamp)));
        }

        return 'Migrations\\' . str_replace(' ', '', ucwords(str_replace('_', ' ', $withoutTimestamp)));
    }

    /**
     * @param string $migrationFileName
     * @return void
     * @throws ReflectionException
     */
    private function deleteMigrationRecord(string $migrationFileName): void
    {
        try {
            App::resolve(Database::class)?->delete('migrations', ['migration' => $migrationFileName]);
        } catch (Exception $e) {
            echo "Failed to delete migration record for $migrationFileName: " . $e->getMessage() . "\n";
        }
    }
}