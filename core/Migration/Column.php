<?php

namespace Core\Migration;

class Column
{
    public bool $nullable = false;
    public mixed $default = null;
    public bool $unique = false;
    public bool $indexed = false;
    public bool $unsigned = false;

    public function __construct(public string $name, public string $type)
    {
    }

    public function nullable(): Column
    {
        $this->nullable = true;
        return $this;
    }

    public function default(mixed $value): Column
    {
        $this->default = $value;
        return $this;
    }

    public function unique(): Column
    {
        $this->unique = true;
        return $this;
    }

    public function index(): Column
    {
        $this->indexed = true;
        return $this;
    }

    public function unsigned(): Column
    {
        $this->unsigned = true;
        return $this;
    }

    public function toSQL(): string
    {
        $sql = "{$this->name} {$this->type}";

        if ($this->unsigned) {
            $sql .= " UNSIGNED";
        }

        $sql .= $this->nullable ? " NULL" : " NOT NULL";

        if ($this->default !== null) {
            $sql .= " DEFAULT '{$this->default}'";
        }

        if ($this->unique) {
            $sql .= " UNIQUE";
        }

        return $sql;
    }
}
