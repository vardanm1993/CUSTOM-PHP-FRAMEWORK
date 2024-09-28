<?php

namespace Core\Console\Traits;

use Core\Exceptions\ContainerException;
use ReflectionException;

trait HasFactoryOrSeeder
{
    /**
     * @throws ContainerException
     * @throws ReflectionException
     */
    protected function make(string $name): void
    {
        $name = ucfirst($name);

        $className = $this->argument('name');

        if (empty($className)) {
            echo "{$className} name is required.\n";
            return;
        }

        if (!str_ends_with($className, $name)) {
            $className .= $name;
        }

        $modifiedName = plural(strtolower($name));
        $filename = $className . '.php';
        $path = dirname(__DIR__, 3) . "/database/{$modifiedName}/" . $filename;

        if (file_exists($path)) {
            echo "{$name} file already exists: $filename\n";
            return;
        }

        $content = $this->getContent($className);

        file_put_contents($path, $content);
        chmod($path, 0777);

        echo "{$name} created: $filename\n";
    }

    abstract protected function getContent(string $className): string;
}