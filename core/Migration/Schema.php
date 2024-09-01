<?php

namespace Core\Migration;

use Closure;
use Core\App;
use Core\Database;
use Core\Exceptions\ContainerException;
use ReflectionException;

class Schema
{
    /**
     * @throws ReflectionException
     * @throws ContainerException
     */
    public function create(string $table, Closure $callback): void
    {
        $blueprint = new Blueprint($table);
        $callback($blueprint);
        $sql = $blueprint->toSQL();

        $this->execute($sql);
    }

    /**
     * @throws ContainerException
     * @throws ReflectionException
     */
    public function dropIfExists($table): void
    {
        $sql = "DROP TABLE IF EXISTS $table";

        $this->execute($sql);
    }

    /**
     * @throws ContainerException
     * @throws ReflectionException
     */
    public function table($table, Closure $callback): void
    {
        $blueprint = new Blueprint($table);
        $callback($blueprint);
        $sql = $blueprint->toSql();

        $this->execute($sql);
    }

    /**
     * @throws ReflectionException
     * @throws ContainerException
     */
    private function execute(string $sql): void
    {
        App::resolve(Database::class)?->execute($sql);
    }
}