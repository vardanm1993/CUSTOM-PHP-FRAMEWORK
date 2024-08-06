<?php

namespace App\Http\Controllers;

class TestController
{
    public function test()
    {
        return 'test';
    }

    public function show($id)
    {
        return $id;
    }
}