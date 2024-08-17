<?php

namespace Core;

use JsonException;

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
        return rtrim(strtok($this->getUri(), '?'), '/');
    }

    public function getBody(): false|string
    {
        return file_get_contents('php://input');
    }


    /**
     * @throws JsonException
     */
    public function getJson(): array
    {
        return json_decode($this->getBody(), true, 512, JSON_THROW_ON_ERROR);
    }

    /**
     * @throws JsonException
     */
    public function all(): array
    {
        $json = $this->getJson() ?? [];

        return array_merge($this->get, $this->post, $this->files, $json);
    }

    /**
     * @throws JsonException
     */
    public function input(string $key, $default = null)
    {
        if (array_key_exists($key, $this->post)) {
            return $this->post[$key];
        }

        if (array_key_exists($key, $this->get)) {
            return $this->get[$key];
        }

        $json = $this->getJson() ?? [];
        if (array_key_exists($key, $json)) {
            return $json[$key];
        }

        return $default;
    }

    /**
     * @throws JsonException
     */
    public function has(string $key): bool
    {
        return array_key_exists($key, $this->post) ||
            array_key_exists($key, $this->get) ||
            (array_key_exists($key, $this->getJson() ?? []));
    }

    /**
     * @throws JsonException
     */
    public function only(array $keys): array
    {
        return array_intersect_key($this->all(), array_flip($keys));
    }

    /**
     * @throws JsonException
     */
    public function except(array $keys): array
    {
        return array_diff_key($this->all(), array_flip($keys));
    }

}