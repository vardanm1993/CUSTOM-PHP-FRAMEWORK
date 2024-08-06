<?php

namespace Core\Route;

use Core\Exceptions\InvalidCallbackException;
use Core\Request;
use Core\Response;

class RouteDispatcher
{
    /**
     * @throws InvalidCallbackException
     */
    public static function dispatch(Request $request): Response
    {
        $method = $request->getMethod();
        $uri = $request->getPathInfo();


        foreach (Route::routes()[$method] as $routeConfig){

            if (preg_match($routeConfig->route, $uri, $matches)){

                $params = array_filter($matches,'is_string', ARRAY_FILTER_USE_KEY);

                $callback = is_array($routeConfig->callback) ?
                    [(new $routeConfig->callback[0]),$routeConfig->callback[1]] :
                    $routeConfig->callback;

                if (!is_callable($callback)) {
                    throw new InvalidCallbackException(
                        "The callback provided is not callable"
                    );
                }



                $response = call_user_func_array($callback, $params);

                if (!$response) {
                    exit();
                }

                return $response instanceof Response ? $response : new Response(content: $response);

            }
        }

        return new Response(content: '404 Not Found', statusCode: 404);
    }
}