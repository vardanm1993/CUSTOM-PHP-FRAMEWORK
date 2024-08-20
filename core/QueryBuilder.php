<?php

namespace Core;

use Core\Exceptions\ContainerException;
use PDO;
use ReflectionException;

class QueryBuilder
{
    protected array $conditions = [];
    protected array $columns = ['*'];

    protected array $bindings = [];

    protected ?int $limit = null;
    protected ?int $offset = null;
    protected ?string $orderBy = null;

    public function __construct(protected string $table)
    {

    }

    public function select(array $columns = ['*']): static
    {
        $this->columns = is_array($columns) ? $columns : func_get_args();
        return $this;
    }

    public function where(string $column , string $operator = null, string|int $value = null): static
    {
        $this->conditions[] = "{$column} {$operator} ?";
        $this->bindings[] = $value;
        return $this;
    }

    public function orderBy(string $column , string $direction = 'asc'): static
    {
        $this->orderBy = "{$column} {$direction}";
        return $this;
    }

    public function limit(int $limit): static
    {
        $this->limit = $limit;
        return $this;
    }

    public function offset(int $offset): static
    {
        $this->offset = $offset;
        return $this;
    }

    protected function buildQuery(): string
    {
        $columns = implode(', ', $this->columns);

        $sql = "SELECT {$columns}  FROM {$this->table}";

        if ($this->conditions){
            $sql .= " WHERE " . implode(' AND ', $this->conditions);
        }

        if ($this->orderBy) {
            $sql .= " ORDER BY " . $this->orderBy;
        }

        if (isset($this->limit)) {
            $sql .= " LIMIT " . $this->limit;
        }

        if (isset($this->offset)) {
            $sql .= " OFFSET " . $this->offset;
        }

        return $sql;
    }

    /**
     * @throws ContainerException
     * @throws ReflectionException
     */
    protected function execute(string $sql, string $className): array
    {
        $db = App::resolve(Database::class);
        $results = $db->execute(sql: $sql, data: $this->bindings)->get([PDO::FETCH_ASSOC]);

        $instances = [];
        foreach ($results as $result) {
            $instance = new $className();
            foreach ($result as $key => $value) {
                $instance->$key = $value;
            }
            $instances[] = $instance;
        }

        return $instances;
    }

    /**
     * @throws ContainerException
     * @throws ReflectionException
     */
    public function get(string $className)
    {
        return $this->execute($this->buildQuery(), $className);
    }

    /**
     * @throws ContainerException
     * @throws ReflectionException
     */
    public function first(string $className)
    {
        $this->limit(1);

        return $this->get($className)[0] ?? null;
    }
}