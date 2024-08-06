<?php

namespace Core\Exceptions;

use Exception;

class InvalidCallbackException extends Exception
{
    public function __construct(string $message = "Invalid callback", int $code = 0, Exception $previous = null)
    {
        parent::__construct(message: $message, code: $code, previous: $previous);
    }
}