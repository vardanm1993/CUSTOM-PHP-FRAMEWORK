<?php

namespace Core;

abstract class Seeder
{
    abstract public function run(): void;

    protected function call(array $seeders): void
    {
        foreach ($seeders as $seeder) {
            (new $seeder)->run();
        }
    }
}