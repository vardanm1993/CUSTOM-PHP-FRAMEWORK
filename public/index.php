<?php
declare(strict_types=1);

use Core\Kernel;
use Core\Request;

define('BASE_PATH', dirname(__DIR__));

require_once BASE_PATH . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(BASE_PATH);
$dotenv->safeLoad();

require base_path('bootstrap.php');
require base_path('routes/web.php');


$kernel = new Kernel();

$request = new Request(
    server: $_SERVER,
    get: $_GET,
    post: $_POST,
    files: $_FILES,
    cookies: $_COOKIE
);

$response = $kernel->handle(request: $request);

$response->send();
