<?php

namespace Core\Console;

use Core\App;
use Core\Exceptions\ContainerException;
use Core\Request;
use ReflectionException;

abstract class Command
{
    protected string $signature;
    protected string $description;

    abstract public function handle();

    /**
     * @return string
     */
    public function getSignature(): string
    {
        return $this->signature;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @throws ReflectionException
     * @throws ContainerException
     */
    public function argument(string $key): ?string
    {
        preg_match_all('/\{(\w+)}/', $this->signature, $matches);
        $placeholders = $matches[1] ?? [];

        $index = array_search($key, $placeholders, true);

        if ($index === false) {
            return null;
        }

        $args = array_slice(App::resolve(Request::class)->server['argv'], 2);

        return $args[$index] ?? null;
    }

    /**
     * @throws ContainerException
     * @throws ReflectionException
     */
    public function option(string $key): ?string
    {
        $pattern = '/--' . preg_quote($key, '/') . '(?:=(.*))?/';

        $args = array_slice(App::resolve(Request::class)->server['argv'], 2);

        foreach ($args as $arg) {
            if (preg_match($pattern, $arg, $matches)) {
                return $matches[1] ?? null;
            }
        }

        return null;
    }

    protected function call(string $commandName): void
    {
        $commands = ConsoleKernel::getCommands();

        if (!isset($commands[$commandName])) {
            echo "Command '{$commandName}' not found.\n";
            return;
        }

        (new $commands[$commandName])->handle();
    }
}