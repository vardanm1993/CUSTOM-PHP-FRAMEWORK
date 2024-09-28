<?php

namespace Core\Console\Traits\Migrations;

use Core\Exceptions\ContainerException;
use ReflectionException;

trait MigrationHasSpecialPropsTrait
{
    /**
     * @throws ContainerException
     * @throws ReflectionException
     */
    private function hasSeed(): void
    {
        if ($this->argument('seed') === '--seed') {
            $this->call('db:seed');
        }
    }
}