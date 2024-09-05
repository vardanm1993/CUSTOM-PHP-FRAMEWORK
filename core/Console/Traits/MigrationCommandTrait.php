<?php

namespace Core\Console\Traits;

trait MigrationCommandTrait
{
    /**
     * @param string $migrationFileName
     * @param false $onlyClass
     * @return string
     */
    private function getMigrationClassName(string $migrationFileName, bool $onlyClass = false): string
    {
        $withoutTimestamp = preg_replace('/^\d{4}_\d{2}_\d{2}_\d+_/', '', $migrationFileName);

        if ($onlyClass){
            return str_replace(' ', '', ucwords(str_replace('_', ' ', $withoutTimestamp)));
        }

        return 'Migrations\\' . str_replace(' ', '', ucwords(str_replace('_', ' ', $withoutTimestamp)));
    }
}