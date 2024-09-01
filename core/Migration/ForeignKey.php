<?php

namespace Core\Migration;

class ForeignKey
{

    public string $onTable;
    public string $onColumn;
    public string $onDelete;

    public string $onUpdate;

    public function __construct(public Blueprint $blueprint, public string $column)
    {

    }

    public function references(string $column): ForeignKey
    {
        $this->onColumn = $column;
        return $this;
    }

    public function on(string $table): ForeignKey
    {
        $this->onTable = $table;
        return $this;
    }

    public function onDelete(string $action): ForeignKey
    {
        $this->onDelete = $action;
        return $this;
    }

    public function onUpdate($action): ForeignKey
    {
        $this->onUpdate = $action;
        return $this;
    }

    protected function buildSQL(): Blueprint
    {
        $foreignKeySql = "FOREIGN KEY ({$this->column}) REFERENCES {$this->onTable}({$this->onColumn})";

        if ($this->onDelete) {
            $foreignKeySql .= " ON DELETE {$this->onDelete}";
        }

        if ($this->onUpdate) {
            $foreignKeySql .= " ON UPDATE {$this->onUpdate}";
        }

        return $this->blueprint->addForeignKey($foreignKeySql);
    }

    protected function tableNameFromColumn(string $column): string
    {
        return str_replace('_id', '', $column) . 's';
    }

    public function constrained(): ForeignKey
    {
        $this->onTable = $this->onTable ?? $this->tableNameFromColumn($this->column);
        $this->onColumn = $this->onColumn ?? 'id';
        return $this->references($this->onColumn)->on($this->onTable);
    }


    public function __destruct()
    {
        $this->buildSQL();
    }
}