<?php

namespace App\Http\Controllers;

use Core\Exceptions\NotFoundTemplate;
use Core\Request;
use Core\Response;

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
     * @throws NotFoundTemplate
     */
    public function show($id): string
    {
        return view('show',compact('id'));
    }

    public function auth(): Response
    {

    }
}