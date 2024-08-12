<?php

namespace App\Http\Controllers;

use Core\Exceptions\ContainerException;
use Core\Exceptions\NotFoundTemplate;
use Core\Redirect;
use Core\Request;
    use ReflectionException;

class TestController
{
    public function __construct(public Request $request)
    {
    }

    /**
     * @throws NotFoundTemplate
     */
    public function test(): string
    {
        return view('index', ['method' => $this->request->getMethod()]);
    }

    /**
     * @throws ReflectionException
     * @throws ContainerException
     */
    public function testRedirect(): Redirect
    {
        return redirect_back('/')->with('id', 1);
    }

    /**
     * @throws NotFoundTemplate
     */
    public function show($id): string
    {
        return view('show',compact('id'));
    }

    public function auth(): Redirect
    {
    }
}