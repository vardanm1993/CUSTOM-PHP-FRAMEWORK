<?php

namespace Core\Route;

class RouteConfig
{
    public string $name = '';

    public array $middlewares = [];

    public function __construct(
        public string $route,
        public string $prettyRoute,
        public array|\Closure $callback,
    )
    {
    }

    public function name(string $name): RouteConfig
    {
        $this->name = $name;
        Route::registerNamedRoute($name, $this);
        return $this;
    }

    public function middleware(array|string $middleware): RouteConfig
    {
        $this->middlewares = is_array($middleware) ? $middleware : [$middleware];

        return $this;
    }


}