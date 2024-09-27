<?php

namespace Seeders;

use Core\Seeder;

class DatabaseSeeder extends Seeder
{

    public function run(): void
    {
        $this->call([
            TestSeeder::class,
        ]);
    }
}