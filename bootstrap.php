<?php

use Core\App;
use Core\Blade;
use Core\Container;
use Core\Request;

$container = new Container();

$container->bind(Request::class, function () {
    return new Request(
        server: $_SERVER,
        get: $_GET,
        post: $_POST,
        files: $_FILES,
        cookies: $_COOKIE
    );
});

$container->bind(Blade::class, function (){

    $patterns = require base_path('config/blade.php');

    return new Blade($patterns);
});

App::setContainer($container);