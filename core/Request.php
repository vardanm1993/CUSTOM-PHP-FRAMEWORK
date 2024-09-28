<?php

namespace Core;

use JsonException;

class Request
{
    public function __construct(
        public array $server,
        public array $get,
        public array $post,
        public array $files,
        public array $cookies,
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
     * @return array
     */
    public function getJson(): array
    {
        $body = $this->getBody();

        if (empty($body)) {
            return [];
        }

        try {
            return json_decode($body, true, 512, JSON_THROW_ON_ERROR);
        } catch (JsonException $e) {
            return [];
        }
    }

    /**
     * @return array
     */
    public function all(): array
    {
        $json = $this->getJson() ?? [];
        return array_merge($this->get, $this->post, $this->files, $json);
    }

    /**
     * @param string $key
     * @param $default
     * @return mixed|null
     */
    public function input(string $key, $default = null): mixed
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
     * @param string $key
     * @return bool
     */
    public function has(string $key): bool
    {
        return array_key_exists($key, $this->post) ||
            array_key_exists($key, $this->get) ||
            (array_key_exists($key, $this->getJson() ?? []));
    }

    /**
     * @param array $keys
     * @return array
     */
    public function only(array $keys): array
    {
        return array_intersect_key($this->all(), array_flip($keys));
    }

    /**
     * @param array $keys
     * @return array
     */
    public function except(array $keys): array
    {
        return array_diff_key($this->all(), array_flip($keys));
    }

}