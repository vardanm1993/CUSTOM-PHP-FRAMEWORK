<?php

namespace Seeders;

use Core\Exceptions\Exception;
use Core\Seeder;
use Factories\TestFactory;

class TestSeeder extends Seeder
{
    /**
     * @throws Exception
     */
    public function run(): void
    {
        TestFactory::create(5);
    }
}