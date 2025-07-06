<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class AdminController extends Controller
{
    public function recentUsers()
    {
        return response()->json(['users' => User::orderBy('created_at', 'desc')->take(10)->get()]);
    }
}
