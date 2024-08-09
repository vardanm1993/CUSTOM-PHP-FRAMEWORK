<?php

use Core\App;
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

App::setContainer($container);