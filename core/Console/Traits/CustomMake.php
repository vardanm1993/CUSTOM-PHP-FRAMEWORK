<?php

namespace Core\Console\Traits;

trait CustomMake
{
    protected function make(string $name, string $directory)
    {
        $name = ucfirst($name);
        $className = $this->argument('name');

        if (empty($className)) {
            echo "{$name} name is required.\n";
            return;
        }

        if (!str_ends_with($className, $name)) {
            $className .= $name;
        }

        $filename = $className . '.php';
        $path = $directory . $filename;

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