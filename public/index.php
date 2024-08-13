<?php
declare(strict_types=1);

session_start();

use Core\App;
use Core\Exceptions\ContainerException;
use Core\Exceptions\InvalidCallbackException;
use Core\Kernel;
use Core\Request;

define('BASE_PATH', dirname(__DIR__));

require_once BASE_PATH . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(BASE_PATH);
$dotenv->safeLoad();

require base_path('bootstrap.php');
require base_path('routes/web.php');


$kernel = new Kernel();


try {
    $response = $kernel->handle(request: App::resolve(Request::class));

    $response->send();

} catch (ContainerException|InvalidCallbackException|ReflectionException $e) {
    echo $e->getMessage();
}

