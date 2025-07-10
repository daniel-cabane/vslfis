<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;

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

    Route::patch('/admin/users/{user}/update', [AdminController::class, 'updateUser']);
    Route::delete('/admin/users/{user}', [AdminController::class, 'deleteUser']);
});


/*
*
*   ADMIN || CPE
* 
*/

Route::group(['middleware'=>['role:admin|cpe']], function(){
    Route::get('/admin/users', [AdminController::class, 'indexUsers']);
    Route::post('/admin/students', [AdminController::class, 'addStudents']);
});


/*
*
*   USER
* 
*/

Route::group(['middleware'=>['auth:sanctum']], function(){
    Route::patch('/user/name', [UserController::class, 'updateName']);
});