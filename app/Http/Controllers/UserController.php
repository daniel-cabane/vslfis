<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
// use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class UserController extends Controller
{
    public function testLogin(Request $request)
    {
        if (App::environment('local')) {
            $userName = $request->validate(['user' => 'required|max:50'])['user'];
    
            $user = User::where('name', $userName)->first();
    
            if($user){
                Auth::login($user);

                $token = $user->createToken('api-token')->plainTextToken;
    
                return response()->json([
                    'user' => $user,
                    'token' => $token,
                    'message' => [
                        'text' => 'User logged in',
                        'type' => 'success'
                    ]
                ]);
            }
            
            return response()->json([
                'message' => [
                    'text' => 'User not found',
                    'type' => 'error'
                ]
            ]);
        }
    }

    public function logout()
    {
        Auth::logout();

        return response()->json([
            'loggedOut' => true,
            'message' => [
                'text' => 'User logged out',
                'type' => 'info'
            ]
        ]);
    }

    public function updateName(Request $request)
    {
        $name = $request->validate(['name' => 'required|max:100'])['name'];
        $user = auth()->user();

        $user->update(['name' => $name]);

        return response()->json(['user' => $user]);
    }
}
