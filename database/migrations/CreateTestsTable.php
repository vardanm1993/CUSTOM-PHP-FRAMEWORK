<?php

namespace Migrations;

use Core\Exceptions\ContainerException;
use Core\Migration\Blueprint;
use Core\Migration\Migration;
use ReflectionException;

class CreateTestsTable extends Migration
{

    /**
     * @throws ReflectionException
     * @throws ContainerException
     */
    public function up(): void
    {
        $this->schema->create('tests', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description');
            $table->timestamps();
        });
    }

    /**
     * @throws ContainerException
     * @throws ReflectionException
     */
    public function down(): void
    {
        $this->schema->dropIfExists('tests');

    }
}