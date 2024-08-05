<?php

namespace Core;

class Request
{
    public function __construct(
        public readonly array $server,
        public readonly array $get,
        public readonly array $post,
        public readonly array $files,
        public readonly array $cookies,
    )
    {
    }

    public function getMethod(): string
    {
        return $this->server['REQUEST_METHOD'];
    }

    public function getUri(): string
    {
        return $this->server['REQUEST_URI'];
    }

    public function getPathInfo(): false|string
    {
        return rtrim(strtok($this->getUri(), '?'),'/');
    }

    public function getBody(): false|string
    {
        return file_get_contents('php://input');
    }


    public function getJson(): array
    {
        return json_decode($this->getBody(), true);
    }


}