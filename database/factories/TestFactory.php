<?php

namespace Factories;

use Core\Factory;

class TestFactory extends Factory
{
    protected function definition(): array
    {
        return [
            'name' => 'test1',
            'description' =>  'test1 description',
        ];
    }
}