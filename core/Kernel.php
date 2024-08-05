<?php

namespace Core;

class Kernel
{
    public function handle(Request $request): Response
    {
        return new Response();
    }
}