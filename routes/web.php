<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\UserController;

Route::get('/', function () {
    return view('welcome');
});

Route::post('/testLogin', [UserController::class, 'testLogin']);

Route::post('/logout', [UserController::class, 'logout']);

Route::get('/{name}', function(){
  return view('welcome');
})->where(['name' => 'home|manage|admin']);

