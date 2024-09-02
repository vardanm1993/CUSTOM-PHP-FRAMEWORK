<?php

namespace Core\Console;

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
}