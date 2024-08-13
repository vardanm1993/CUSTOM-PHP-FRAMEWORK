<?php

namespace Core;

use Core\Exceptions\InvalidCallbackException;
use Core\Route\RouteDispatcher;
use ReflectionException;

class Kernel
{
    /**
     * @param Request $request
     * @return Response
     * @throws Exceptions\ContainerException
     * @throws InvalidCallbackException
     * @throws ReflectionException
     */
    public function handle(Request $request): Response
    {
        return RouteDispatcher::dispatch($request);
    }
}