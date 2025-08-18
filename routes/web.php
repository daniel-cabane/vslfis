<?php

use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;

use App\Http\Controllers\UserController;

Route::get('/', function () {
    return view('welcome');
});

Route::post('/testLogin', [UserController::class, 'testLogin']);

Route::post('/logout', [UserController::class, 'logout']);

Route::get('/{name}', function(){
  return view('welcome');
})->where(['name' => 'home|manage|admin|history|unfinalized|exits']);

Route::get('/auth/google', [UserController::class, 'googleSigninRedirect']);
Route::get('/auth/google/callback', [UserController::class, 'googleSigninCallback']);

// Route::get('/auth/redirect', function () {
//     return Socialite::driver('github')->redirect();
// });

// Route::get('/auth/callback', function () {
//     $user = Socialite::driver('github')->user();

//     // $user->token
// });

