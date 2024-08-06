<?php

namespace Core\Route;

class Route
{
    private static array $routes = [];
    private static array $namedRoutes = [];

    public static function routes(): array
    {
        return self::$routes;
    }

    protected static function add(string $method, string $uri, array|callable $callback): RouteConfig
    {
        $uri = ensure_leading_slash($uri);

        $routePattern = self::makeRegExp($uri);

        $routeConfig = new RouteConfig(route: $routePattern, prettyRoute: $uri, callback: $callback);
        self::$routes[$method][$routePattern] = $routeConfig;

        return $routeConfig;
    }

    public static function get(string $uri, array|callable $callback): RouteConfig
    {
        return static::add(method: 'GET', uri: $uri, callback: $callback);
    }

    public static function post(string $uri, array|callable $callback): RouteConfig
    {
        return static::add(method: 'POST', uri: $uri, callback: $callback);
    }

    public static function patch(string $uri, array|callable $callback): RouteConfig
    {
        return static::add(method: 'PATCH', uri: $uri, callback: $callback);
    }

    public static function put(string $uri, array|callable $callback): RouteConfig
    {
        return static::add(method: 'PUT', uri: $uri, callback: $callback);
    }

    public static function delete(string $uri, array|callable $callback): RouteConfig
    {
        return static::add(method: 'DELETE', uri: $uri, callback: $callback);
    }

    public static function registerNamedRoute(string $name, RouteConfig $routeConfig): void
    {
        self::$namedRoutes[$name] = $routeConfig;
    }

    private static function makeRegExp(string $uri): string
    {
        $routePattern = preg_replace(
            '/\{(\w+)}/',
            '(?P<$1>\w+)',
            rtrim($uri, '/')
        );

        return '@^' . $routePattern . '$@';

    }

    public static function getRouteByName(string $name): ?RouteConfig
    {
        return self::$namedRoutes[$name] ?? null;
    }

    public static function generateUrl(string $name, array $params = []): string
    {
        $routeConfig = self::getRouteByName($name);

        if (!$routeConfig) {
            throw new \RuntimeException("Route {$name} not found.");

        }

        $route = $routeConfig->prettyRoute;


        foreach ($params as $key => $value) {
            $route = preg_replace('/{' . $key . '}/', $value, $route);
        }

        if (preg_match('/{.*?}/', $route)) {
            throw new \RuntimeException("Not all parameters for route {$name} have been provided.");
        }

        return $route;
    }


}