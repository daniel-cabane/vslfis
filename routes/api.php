<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

/*
*
*   ADMIN
* 
*/

Route::group(['middleware'=>['role:admin']], function(){
    Route::get('/admin/users/recent', [AdminController::class, 'recentUsers']);
    Route::get('/admin/users/search', [AdminController::class, 'searchUsers']);
});