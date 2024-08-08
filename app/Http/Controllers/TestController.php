<?php

namespace App\Http\Controllers;

use Core\Exceptions\NotFoundTemplate;

class TestController
{

    /**
     * @throws NotFoundTemplate
     */
    public function test(): string
    {
        return view('index');
    }

    /**
     * @throws NotFoundTemplate
     */
    public function show($id): string
    {
        return view('show',compact('id'));
    }
}