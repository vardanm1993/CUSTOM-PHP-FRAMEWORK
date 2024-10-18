<?php

use App\Http\Controllers\TestController;
use Core\Route\Route;

Route::get('/', [TestController::class,'test']);
Route::get('/test/{id}', [TestController::class,'show']);
Route::get('/auth', [TestController::class,'auth'])->middleware('auth')->name('test.auth');
Route::post('/store', [TestController::class,'store']);
