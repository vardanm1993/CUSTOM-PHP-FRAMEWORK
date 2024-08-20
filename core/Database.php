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

}