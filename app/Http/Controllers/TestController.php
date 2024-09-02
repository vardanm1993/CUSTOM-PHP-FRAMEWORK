<?php

namespace App\Http\Controllers;

use App\Http\Models\Test;
use Core\Exceptions\ContainerException;
use Core\Exceptions\NotFoundTemplate;
use Core\Redirect;
use Core\Request;
use Core\Validator;
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
        return redirect_back()->with('id', 1);
    }


    /**
     * @throws ContainerException
     * @throws ReflectionException
     */
    public function store(): Redirect
    {

        $validator = Validator::make($this->request->all(), [
            'name' => 'required|string|min:3|max:20',
            'description' => 'required|string'
        ]);

        $data = $validator->validated();

        if ($validator->fails()) {
            redirect('/');
        }


        Test::create($data);

        return redirect('/test/2');
    }

    /**
     * @param $id
     * @return string
     * @throws ContainerException
     * @throws NotFoundTemplate
     * @throws ReflectionException
     */
    public function show($id): string
    {
        $test = Test::find($id);
        return view('show', compact('test'));
    }

    /**
     * @throws NotFoundTemplate
     */
    public function auth(): string
    {
        return view('index');
    }
}