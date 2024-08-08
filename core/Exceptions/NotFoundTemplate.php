<?php

namespace Core\Exceptions;

use Exception;

class NotFoundTemplate extends Exception
{
    public function __construct(string $message = "Not found template", int $code = 0, Exception $previous = null)
    {
        parent::__construct(message: $message, code: $code, previous: $previous);
    }
}