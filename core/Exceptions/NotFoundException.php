<?php

namespace Core\Exceptions;

class NotFoundException extends Exception
{
    public function __construct(string $message = 'Not found', int $code = 0, Exception $previous = null)
    {
        parent::__construct(message: $message, code: $code, previous: $previous);
    }
}