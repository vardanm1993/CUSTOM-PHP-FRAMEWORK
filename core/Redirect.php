<?php

namespace Core;

use Core\Exceptions\ContainerException;
use ReflectionException;

class Redirect
{
    private Validator $validator;

    public function __construct(protected string $url)
    {
    }

    public static function to(string $url): Redirect
    {
        header('Location: ' . $url);
        return new self($url);
    }

    /**
     * @throws ContainerException
     * @throws ReflectionException
     */
    public static function back(): Redirect
    {
        $url = App::resolve(Request::class)?->server['HTTP_REFERER'] ?? '/';

        header('Location: ' . $url);
        return new self($url);
    }

    /**
     * @throws ContainerException
     * @throws ReflectionException
     */
    public static function backWith(string $key, mixed $value): Redirect
    {
        return self::back()?->with($key, $value);
    }

    public function with(string $key, mixed $value): Redirect
    {
        Session::flash($key, $value);
        return $this;
    }

    public function withErrors(Validator $validator): Redirect
    {
        $this->validator = $validator;
        Session::flash('errors', $validator->errors());
        return $this;
    }

    public function withInput(?Validator $validator): Redirect
    {
        Session::flash('old', $this->validator->getData() ?? []);
        return $this;
    }
}