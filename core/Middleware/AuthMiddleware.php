<?php

namespace Core\Middleware;

use Core\Exceptions\NotFoundTemplate;
use Core\Request;
use Core\Response;
use Core\View;

class AuthMiddleware implements MiddlewareInterface
{

    /**
     * @throws NotFoundTemplate
     */
    public function handle(Request $request, callable $next): Response
    {
        if (! $this->isAuthenticated()){
            return new Response(View::render('errors/401'),401);
        }

        return $next($request);
    }

    private function isAuthenticated(): bool
    {
        return isset($_SESSION['user']);
    }
}