<?php

namespace Core;

use Core\Exceptions\ContainerException;
use Core\Exceptions\Exception;
use ReflectionClass;
use ReflectionException;

class Model
{
    protected string $table;
    protected string $primaryKey = 'id';

    public function __construct(protected array $attributes = [])
    {
        $className = (new ReflectionClass(static::class))->getShortName();

        $this->table ??= strtolower($className) . 's';
    }

    public static function query(): QueryBuilder
    {
        return new QueryBuilder((new static())->table);
    }

    public static function where(string $column, string $operator = null, $value = null): QueryBuilder
    {
        return static::query()->where(column: $column, operator: $operator, value: $value);
    }

    /**
     * @throws ContainerException
     * @throws ReflectionException
     */
    public static function find(int $id)
    {
        return static::query()->where((new static())->primaryKey, '=', $id)->first(static::class);
    }

    /**
     * @throws ReflectionException
     * @throws ContainerException
     */
    public static function all(): array
    {
        return static::query()->get(static::class);
    }

    /**
     * @throws ContainerException
     * @throws ReflectionException
     */
    public function save(): void
    {
        $db = App::resolve(Database::class);

        if (isset($this->attributes[$this->primaryKey])) {
            $set = '';
            $bindings = [];

            foreach ($this->attributes as $key => $value) {
                if ($key !== $this->primaryKey) {
                    $set .= "{$key} = ?, ";
                    $bindings[] = $value;
                }
            }

            $set = rtrim($set, ', ');
            $bindings[] = $this->attributes[$this->primaryKey];

            $sql = "UPDATE {$this->table} SET {$set} WHERE {$this->primaryKey} = ?";
            $db->execute(sql: $sql, data: $bindings);

        } else {
          $db->insert($this->table, $this->attributes);
        }


        if (!isset($this->attributes[$this->primaryKey])) {
            $this->attributes[$this->primaryKey] = $db->lastInsertId();
        }
    }

    /**
     * @throws ContainerException
     * @throws ReflectionException
     */
    public static function create(array $attributes): static
    {
        $instance = new static($attributes);
        $instance->save();
        return $instance;
    }

    /**
     * @throws ReflectionException
     * @throws ContainerException
     */
    public function update(array $attributes): static
    {
        foreach ($attributes as $key => $value) {
            $this->attributes[$key] = $value;
        }
        $this->save();
        return $this;
    }

    /**
     * @throws Exception
     * @throws ReflectionException
     */
    public function delete(): bool
    {
        if (!isset($this->attributes[$this->primaryKey])) {
            throw new Exception("Cannot delete a model that hasn't been saved.");
        }

        $sql = "DELETE FROM {$this->table} WHERE {$this->primaryKey} = ?";

        $result = App::resolve(Database::class)
            ?->execute($sql, [$this->attributes[$this->primaryKey]]);

        return (bool)$result;
    }


    public function __get($key)
    {
        return $this->attributes[$key] ?? null;
    }

    public function __set($key, $value)
    {
        $this->attributes[$key] = $value;
    }

    public function __isset($key)
    {
        return isset($this->attributes[$key]);
    }

}