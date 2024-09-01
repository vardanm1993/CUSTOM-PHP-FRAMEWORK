<?php

namespace Core\Migration;

abstract class Migration
{
    public Schema $schema;

    public function __construct()
    {
        $this->schema = new Schema();
    }

    abstract public function up();

    abstract public function down();
}