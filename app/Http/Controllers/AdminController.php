<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class AdminController extends Controller
{
    public function indexUsers()
    {
        return response()->json([
            'users' => User::where('id', '!=', 1)->get()
        ]);
    }

    public function recentUsers()
    {
        return response()->json([
            'users' => User::where('id', '!=', 1)->orderBy('created_at', 'desc')->take(15)->get()
        ]);
    }

    public function searchUsers(Request $request)
    {
        $name = $request->validate(['name' => 'required|max:100'])['name'];

        return response()->json([
            'users' => User::where('id', '!=', 1)
                ->where(function($query) use ($name) {
                    $query->where('name', 'like', "%$name%")
                        ->orWhere('email', 'like', "%$name%");
                })
                ->take(15)
                ->get()
        ]);
    }

    public function updateUser(User $user, Request $request)
    {
        $attrs = $request->validate([
            'name' => 'required|max:100',
            'email' => 'required|email|max:100',
            'isCPE' => 'required|boolean',
        ]);

        $user->update([
            'name' => $attrs['name'], 'email' => $attrs['email']
        ]);

        if(!$user->is['cpe'] && $attrs['isCPE']){
            $user->assignRole('cpe');
        }
        if($user->is['cpe'] && !$attrs['isCPE']){
            $user->removeRole('cpe');
        }

        return response()->json([
            'user' => $user,
            'message' => [
                        'text' => 'User updated',
                        'type' => 'success'
                    ]
        ]);
    }

    public function deleteUser(User $user)
    {
        $user->delete();

        return response()->json([
            'success' => true,
            'message' => [
                        'text' => 'User deleted',
                        'type' => 'info'
                    ]
        ]);
    }
}
