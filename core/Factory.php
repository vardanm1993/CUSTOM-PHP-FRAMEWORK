<?php

namespace Core;

use Core\Exceptions\Exception;
use ReflectionClass;

abstract class Factory
{
    protected ?string $model = null;

    abstract protected function definition(): array;

    /**
     * @throws Exception
     */
    protected function getModelClassName(): ?string
    {
        if ($this->model !== null) {
            if (!class_exists($this->model)) {
                throw new Exception("The model '{$this->model}' does not exist or is incorrectly specified.
                 Use the full class name, such as 'App\\Http\\Models\\User::class'.");
            }

            return $this->model;
        }

        $factory = (new ReflectionClass($this))->getShortName();

        $modelClassName = 'App\\Http\\Models\\' . str_replace('Factory', '', $factory);

        if (!class_exists($modelClassName)) {
            throw new Exception("The model {$modelClassName} does not exist. 
            Specify the model class in the \$model property.");
        }

        return $modelClassName;
    }

    /**
     * @throws Exception
     */
    protected function makeAttributes(int $count, array $overrides = []): array|object
    {
        $modelClassName = $this->getModelClassName();
        $attributes = array_merge($this->definition(), $overrides);

        if ($count === 1) {
            return new $modelClassName($attributes);
        }

        $instances = [];

        for ($i = 0; $i < $count; $i++) {
            $instances[] = new $modelClassName($attributes);
        }

        return $instances;
    }

    /**
     * @throws Exception
     */
    public static function create(int $count = 1, array $overrides = []): array|object
    {
        $factory = new static();
        $instances = $factory->makeAttributes($count, $overrides);

        if (is_object($instances)) {
            $instances->save();
            return $instances;
        }

        foreach ($instances as $instance) {
            $instance->save();
        }

        return $instances;
    }

}