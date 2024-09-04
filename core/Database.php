<?php

namespace Core;

use Core\Exceptions\NotFoundException;
use Core\Exceptions\QueryException;
use PDO;
use PDOStatement;

class Database
{
    private PDO $connection;
    private PDOStatement $stmt;

    public function __construct(private readonly array $config)
    {
        $dsn = 'mysql:' . http_build_query($this->config, '', '; ');

        $this->connection = new PDO(dsn: $dsn, options: [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);
    }

    public function getConnection(): PDO
    {
        return $this->connection;
    }

    /**
     * @throws QueryException
     */
    public function execute(string $sql, array $data = []): Database
    {
        $this->stmt = $this->connection->prepare($sql);

        if (!$this->stmt->execute($data)) {
            throw new QueryException();
        }
        return $this;
    }

    public function query(string $sql, array $data = []): array|false
    {
        try {
            $this->stmt = $this->connection->prepare($sql);
            $this->stmt->execute($data);
            return $this->stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            echo "Query failed: " . $e->getMessage();
            return false;
        }
    }

    /**
     * @throws NotFoundException
     */
    public function first(array $data = []): mixed
    {
        if (!$data) {
            return $this->stmt->fetch()
                ?: throw new NotFoundException('Record not found');
        }

        return $this->stmt->fetch(...$data)
            ?: throw new NotFoundException('Record not found');
    }

    /**
     * @throws NotFoundException
     */
    public function get(array $data = []): array
    {
        if (!$data) {
            return $this->stmt->fetchAll()
                ?: throw new NotFoundException('Record not found');
        }

        return $this->stmt->fetchAll(...$data)
            ?: throw new NotFoundException('Record not found');
    }

    public function lastInsertId(): int
    {
        return $this->connection->lastInsertId();
    }

    /**
     * @throws QueryException
     */
    public function insert(string $table, array $attributes): void
    {
        $columns = implode(', ', array_keys($attributes));
        $placeholders = implode(', ', array_fill(0, count($attributes), '?'));
        $bindings = array_values($attributes);

        $sql = "INSERT INTO {$table} ({$columns}) VALUES ({$placeholders})";

        $this->execute($sql, $bindings);
    }

    public function select(
        string       $table,
        array|string $columns = '*',
        array        $conditions = [],
        string       $orderBy = '',
        int          $limit = 0,
        int          $offset = 0
    ): array
    {
        $columns = is_array($columns) ? implode(', ', $columns) : $columns;

        $sql = "SELECT {$columns} FROM {$table}";

        $bindings = [];

        if (!empty($conditions)) {
            $conditionStrings = [];
            foreach ($conditions as $column => $value) {
                $conditionStrings[] = "{$column} = ?";
                $bindings[] = $value;
            }
            $sql .= " WHERE " . implode(' AND ', $conditionStrings);
        }

        if ($orderBy) {
            $sql .= " ORDER BY {$orderBy}";
        }

        if ($limit > 0) {
            $sql .= " LIMIT {$limit}";
        }

        if ($offset > 0) {
            $sql .= " OFFSET {$offset}";
        }

        return $this->query($sql, $bindings);
    }

    /**
     * @throws QueryException
     */
    public function delete(string $table, array $conditions = []): void
    {
        if (empty($conditions)) {
            throw new \InvalidArgumentException('Conditions are required for a DELETE operation.');
        }

        $sql = "DELETE FROM {$table}";

        $bindings = [];
        $conditionStrings = [];

        foreach ($conditions as $column => $value) {
            $conditionStrings[] = "{$column} = ?";
            $bindings[] = $value;
        }

        $sql .= " WHERE " . implode(' AND ', $conditionStrings);

        $this->execute($sql, $bindings);
    }
}