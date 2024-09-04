<?php

namespace Core\Console\Traits;

trait MigrationCommandTrait
{
    /**
     * @param string $migrationFileName
     * @return string
     */
    private function getMigrationClassName(string $migrationFileName): string
    {
        $withoutTimestamp = preg_replace('/^\d{4}_\d{2}_\d{2}_\d+_/', '', $migrationFileName);
        return 'Migrations\\' . str_replace(' ', '', ucwords(str_replace('_', ' ', $withoutTimestamp)));
    }
}