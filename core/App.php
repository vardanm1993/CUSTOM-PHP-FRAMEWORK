<?php

namespace Core;

use Core\Exceptions\ContainerException;
use ReflectionException;

class App
{
    private static Container $container;

    public static function setContainer(Container $container): void
    {
        self::$container = $container;
    }

    public static function container(): Container
    {
        return self::$container;
    }

    public static function bind(string $key, callable $resolver): void
    {
        self::$container->bind(key: $key, resolver: $resolver);
    }

    /**
     * @throws ReflectionException
     * @throws ContainerException
     */
    public static function resolve(string $key)
    {
        return self::$container->resolve($key);
    }

}