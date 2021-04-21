<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use app\Http\Requests\ForgetPassword;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Mail\forgotPassword;

class ForgotPasswordController extends Controller
{
    public function postEmail(Request $request)
    {
        $token = Str::random(64);
        DB::table('password_resets')->insert(
            ['email' => $request->email, 'token' => $token, 'created_at' => Carbon::now()]
        );
        $reset = [
            'email' => $request->email,
            'token' => $token,
            'created_at' => Carbon::now()
        ];
        Mail::to($request->email)
            ->send(new forgotPassword($reset));
        return response()->json(['message' => "reset email sended", 'succes' => 1, 'status' => 200]);
    }
}
