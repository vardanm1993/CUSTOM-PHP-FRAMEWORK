<?php

namespace Core\Exceptions;

class ContainerException extends Exception
{
    public function __construct(string $message = "Failed to resolve", int $code = 0, Exception $previous = null)
    {
        parent::__construct(message: $message, code: $code, previous: $previous);
    }
}