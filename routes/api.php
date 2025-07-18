<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\ReportController;

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
    Route::patch('/admin/students/{student}', [AdminController::class, 'updateStudent']);
    Route::post('/admin/students/{student}/photo', [AdminController::class, 'updateStudentPhoto']);
    Route::delete('/admin/students/{student}', [AdminController::class, 'deleteStudent']);
    Route::get('/admin/students/badgeless', [AdminController::class, 'badgelessStudents']);
    Route::get('/admin/students/search', [AdminController::class, 'searchStudents']);
});


/*
*
*   USER
* 
*/

Route::group(['middleware'=>['auth:sanctum']], function(){
    Route::patch('/user/name', [UserController::class, 'updateName']);
});

/*
*
*   STUDENTS AND REPORTS
* 
*/

Route::group(['middleware'=>['auth:sanctum']], function(){
    Route::get('/students/search', [StudentController::class, 'searchStudents']);
    
    Route::get('/reports', [ReportController::class, 'index']);
    Route::get('/reports/myReports', [ReportController::class, 'myReports']);
    Route::post('/reports', [ReportController::class, 'store']);
    Route::post('/reports/{report}/file', [ReportController::class, 'file']);
});

Route::group(['middleware'=>['can:update,report']], function(){
    Route::patch('/reports/{report}', [ReportController::class, 'update']);
    Route::delete('/reports/{report}', [ReportController::class, 'destroy']);
});