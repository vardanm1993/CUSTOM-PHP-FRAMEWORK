<?php

namespace Core\Middleware;

use Core\Request;
use Core\Response;

class GuestMiddleware implements MiddlewareInterface
{
    public function handle(Request $request, callable $next): Response
    {
        if ($this->isAuthenticated()) {
            redirect('/');
        }

        return $next($request);
    }

    private function isAuthenticated(): bool
    {
        return isset($_SESSION['user']);
    }
}