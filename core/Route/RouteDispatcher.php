<?php

namespace Core\Route;

use Core\App;
use Core\Exceptions\ContainerException;
use Core\Exceptions\InvalidCallbackException;
use Core\Middleware\MiddlewareResolver;
use Core\Request;
use Core\Response;
use ReflectionException;

class RouteDispatcher
{
    /**
     * @param Request $request
     * @return Response
     * @throws InvalidCallbackException
     * @throws ContainerException
     * @throws ReflectionException
     */
    public static function dispatch(Request $request): Response
    {
        $method = $request->getMethod();
        $uri = $request->getPathInfo();

        foreach (Route::routes()[$method] as $routeConfig) {

            if (preg_match($routeConfig->route, $uri, $matches)) {

                $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);

                $callback = is_array($routeConfig->callback) ?
                    [App::resolve($routeConfig->callback[0]), $routeConfig->callback[1]] :
                    $routeConfig->callback;

                if (!is_callable($callback)) {
                    throw new InvalidCallbackException(
                        "The callback provided is not callable"
                    );
                }

                if ($routeConfig->middlewares) {
                    $response = MiddlewareResolver::resolve(
                        middlewares: $routeConfig->middlewares,
                        request: $request,
                        next: static fn(Request $request) => call_user_func_array($callback, $params)
                    );
                } else {
                    $response = call_user_func_array($callback, $params);
                }

                if (!$response) {
                    exit();
                }

                return $response instanceof Response ? $response : new Response(content: $response);

            }
        }

        return new Response(content: '404 Not Found', statusCode: 404);
    }
}