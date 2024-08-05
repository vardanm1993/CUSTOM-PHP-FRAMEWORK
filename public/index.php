<?php
declare(strict_types=1);

use Core\Kernel;
use Core\Request;

define('BASE_PATH', dirname(__DIR__));

require_once BASE_PATH . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(BASE_PATH);
$dotenv->safeLoad();

require base_path('web.php');


$kernel = new Kernel();

$request = new Request();

$response = $kernel->handle(request: $request);

$response->send();
