<?php

namespace Core\Middleware;

use Core\App;
use Core\Exceptions\ContainerException;
use Core\Request;
use ReflectionException;

class MiddlewareResolver
{
    public const array MAP = [
        'auth' => AuthMiddleware::class,
        'guest' => GuestMiddleware::class
    ];

    /**
     * @throws ContainerException
     * @throws ReflectionException
     */
    public static function resolve(array $middlewares, Request $request, callable $next = null)
    {
        $middleware = array_shift($middlewares);

        if (array_key_exists($middleware, self::MAP)) {
            $middleware = self::MAP[$middleware];
        }

        if (!$middleware) {
            return $next;
        }

        return (App::resolve($middleware))?->handle(
            $request,
            fn(Request $request) => self::resolve(
                middlewares: $middleware,
                request: $request,
                next: $next
            )
        );
    }
}