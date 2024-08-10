<?php

namespace Core\Middleware;

use Core\Request;
use Core\Response;

interface MiddlewareInterface
{
    public function handle(Request $request, callable $next): Response;
}