<?php

namespace Core;

class Session
{

    public static function has(string $key): bool
    {
        return (bool)static::get($key);
    }

    public static function put(string $key, mixed $value): void
    {
        $_SESSION[$key] = $value;
    }

    public static function get(string $key, $default = null)
    {

        return $_SESSION['_flash'][$key] ?? $_SESSION[$key] ?? $default;
    }

    public static function flash(string $key, mixed $value): void
    {
        $_SESSION['_flash'][$key] = $value;
    }

    public static function old(string $key, $default = null)
    {
        return static::get('old')[$key] ?? $default;
    }

    public static function unflash(): void
    {
        unset($_SESSION['_flash']);
    }

    public static function flush(): void
    {
        $_SESSION = [];
    }

    public static function destroy(): void
    {
        static::flush();

        session_destroy();

        $params = session_get_cookie_params();

        setcookie('PHPSESID', time() - 3600, $params['path'], $params['domain'], $params['secure'], $params['httponly']);

    }
}