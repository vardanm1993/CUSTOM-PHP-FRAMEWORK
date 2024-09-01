<?php

namespace Core\Migration;

class Blueprint
{
    public array $columns = [];
    public array $indexes = [];
    public array $foreignKeys = [];

    public function __construct(public string $table)
    {

    }

    protected function addColumn(string $name, string $type): Column
    {
        $column = new Column($name, $type);
        $this->columns[] = $column;
        return $column;
    }

    public function id(): Column
    {
        return $this->addColumn('id', 'INT AUTO_INCREMENT PRIMARY KEY');

    }

    public function string(string $column, int $length = 256): Column
    {
      return $this->addColumn($column, "VARCHAR({$length})");
    }

    public function integer(string $column, int $length = 11): Column
    {
       return $this->addColumn($column, "INT({$length})");
    }

    public function boolean(string $column, bool $default = false): Column
    {
        return $this->addColumn($column, "BOOLEAN({$default})");
    }

    public function timestamps(): Blueprint
    {
        $this->columns[] = new Column('created_at', 'TIMESTAMP DEFAULT CURRENT_TIMESTAMP');
        $this->columns[] = new Column('updated_at', 'TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP');
        return $this;
    }

    public function foreignId(string $column): ForeignKey
    {
        $this->addColumn($column, 'INT UNSIGNED');
        return new ForeignKey($this, $column);
    }

    public function unique(array $columns): Blueprint
    {
        $this->indexes[] = "UNIQUE(" . implode(', ', $columns) . ")";
        return $this;
    }

    public function index(array $columns): Blueprint
    {
        $this->indexes[] = "INDEX(" . implode(', ', $columns) . ")";
        return $this;
    }

    public function foreign(string $column): ForeignKey
    {
        return new ForeignKey(blueprint: $this, column: $column);
    }

    public function addForeignKey(string $foreignKeySql): Blueprint
    {
        $this->foreignKeys[] = $foreignKeySql;
        return $this;
    }

    public function toSQL(): string
    {
        $sql = "CREATE TABLE IF NOT EXISTS {$this->table} (";

        $columns = array_map(static function(Column $column) {
            return $column->toSQL();
        }, $this->columns);

        $sql .= implode(', ', $columns);

        if (!empty($this->indexes)) {
            $sql .= ', ' . implode(', ', $this->indexes);
        }

        if (!empty($this->foreignKeys)) {
            $sql .= ', ' . implode(', ', $this->foreignKeys);
        }

        return $sql . ");";
    }
}