<?php

use App\Http\Controllers\TestController;
use Core\Route\Route;

Route::get('/', [TestController::class,'test']);
Route::get('/test/{id}', [TestController::class,'show']);