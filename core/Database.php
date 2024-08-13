<?php

namespace Core;

use Core\Exceptions\NotFoundException;
use Core\Exceptions\QueryException;
use PDO;
use PDOStatement;
use stdClass;

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

    /**
     * @throws QueryException
     */
    public function query(string $sql, array $data = []): Database
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
    public function get(): array
    {
        return $this->stmt->fetchAll(PDO::FETCH_OBJ)
            ?: throw new NotFoundException('Record not found');
    }

    /**
     * @throws NotFoundException
     */
    public function find(): stdClass
    {
        return $this->stmt->fetch(PDO::FETCH_OBJ)
            ?: throw new NotFoundException('Record not found');
    }
}