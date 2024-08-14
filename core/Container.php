<?php

namespace Core;

use Core\Exceptions\ContainerBuildException;
use Core\Exceptions\ContainerClassException;
use Core\Exceptions\ContainerDependencyException;
use Core\Exceptions\ContainerException;
use ReflectionClass;
use ReflectionException;

class Container
{
    private array $bindings = [];

    private array $instances = [];


    public function bind(string $key, callable $resolver): void
    {
        $this->bindings[$key] = $resolver;
    }

    /**
     * @throws ContainerException|ReflectionException
     */
    public function resolve(string $key)
    {
        if (isset($this->instances[$key])) {
            return $this->instances[$key];
        }

        if (array_key_exists($key, $this->bindings)) {

            $resolver = $this->bindings[$key];

            try {
                return $this->instances[$key] = $resolver();
            } catch (\Throwable $e) {
                throw new ContainerException("Failed to resolve '{$key}': " . $e->getMessage(), $e->getCode());
            }
        }

        return $this->build($key);
    }

    /**
     * @throws ReflectionException
     * @throws ContainerException
     */
    private function build(string $key)
    {
        try {
            $reflector = new ReflectionClass($key);

            if (!$reflector->isInstantiable()) {
                throw new ContainerClassException("Class '{$key}' is not instantiable.");
            }

            $constructor = $reflector->getConstructor();

            if (is_null($constructor) || !$constructor->getParameters()) {
                return $reflector->newInstance();
            }

            $parameters = $constructor->getParameters();
            $dependencies = $this->resolveDependencies($parameters);
            $instance = $reflector->newInstanceArgs($dependencies);

            return $this->instances[$key] = $instance;

        } catch (ReflectionException $e) {
            throw new ContainerBuildException("Failed to build '{$key}': " . $e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * @throws ContainerDependencyException
     * @throws ContainerException
     * @throws ReflectionException
     */
    private function resolveDependencies(array $parameters): array
    {
        $dependencies = [];

        foreach ($parameters as $parameter) {

            $dependencyType = $parameter->getType();


            if ($dependencyType === null) {
                if ($parameter->isDefaultValueAvailable()) {
                    $dependencies[] = $parameter->getDefaultValue();
                } else {
                    throw new ContainerDependencyException("Cannot resolve class dependency {$parameter->name}");
                }
            } else {
                $dependencyClass = $dependencyType->getName();
                $dependencies[] = $this->resolve($dependencyClass);
            }
        }

        return $dependencies;
    }

}