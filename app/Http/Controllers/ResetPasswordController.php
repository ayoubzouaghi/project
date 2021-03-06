<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ResetPasswordController extends Controller
{
    public function updatePassword(Request $request)
    {
        $updatePassword = DB::table('password_resets')
            ->where(['email' => $request->email, 'token' => $request->token])
            ->first();
        if ($updatePassword) {
            User::where('email', $request->email)
                ->update(['password' => bcrypt($request->password)]);
            DB::table('password_resets')->where(['email' => $request->email])->delete();

            return response()->json(['message'  => "Changed password", 'success' => 1, 'status' => 200]);
        } else {
            return response()->json(['message'  => "Invalid", 'success' => -1, 'status' => 400]);
        }
    }
}
