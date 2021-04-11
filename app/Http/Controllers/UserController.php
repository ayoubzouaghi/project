<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function Login(LoginRequest $request)
    {
        $user  = User::where('email', $request->email)->first();
    }
}
