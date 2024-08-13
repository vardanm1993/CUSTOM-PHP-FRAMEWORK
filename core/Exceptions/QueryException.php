<?php

namespace Core\Exceptions;

class QueryException extends Exception
{
    public function __construct(string $message = 'Query execution failed', int $code = 0, Exception $previous = null)
    {
        parent::__construct(message: $message, code: $code, previous: $previous);
    }
}