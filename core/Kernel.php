<?php

namespace Core;

use Core\Exceptions\InvalidCallbackException;
use Core\Route\RouteDispatcher;

class Kernel
{
    /**
     * @throws InvalidCallbackException
     */
    public function handle(Request $request): Response
    {
        return RouteDispatcher::dispatch($request);
    }
}