<?php

namespace App\Http\Controllers;

use App\Http\Requests\AdminRegisterRequest;
use App\Http\Requests\AdminRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\UserRequest;
use App\Mail\CreateAcount;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class UserController extends Controller
{


    public function __construct()
    {

        $this->client = DB::table('oauth_clients')->where('name', env("APP_NAME") . 'Laravel Password Grant Client')->first();
    }

    public function Login(LoginRequest $request)
    {
        $http = new \GuzzleHttp\Client;
        $user  = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json(['message'  => "Invalid email", 'success' => -1, 'status' => 400]);
        } else {
            if (!(Hash::check($request->password, $user->password))) {
                return response()->json(['message'  => "Invalid password", 'success' => -1, 'status' => 400]);
            } else {
                $response = $http->post(url('/oauth/token'), [
                    'form_params' => [
                        'grant_type'  => 'password',
                        'client_id'   => $this->client->id,
                        'client_secret' => $this->client->secret,
                        'username'    => $request->email,
                        'password'    => $request->password,
                        'scope'       => '',
                    ],
                ]);

                if ($user->getRoleNames()[0] == "admin") {
                    $role = "1";
                } else {
                    $role = "-1";
                }
                $token = json_decode((string) $response->getBody(), true);
                if ($token) {
                    return response()->json(['message'  => "your are welcome", 'success' => 1, 'status' => 200, 'token' => $token, 'role' => $role]);
                } else {
                    return response()->json(['message'  => "Sorry ! something went wrong", 'success' => -1, 'status' => 400]);
                }
            }
        }
    }

    public function addUser(AdminRegisterRequest $request)
    {
        $user  = User::where('email', $request->email)->first();
        if ($user) {
            return response()->json(['message'  => "Sorry! this email is already registered", 'success' => -1, 'status' => 400, 'user' => $user]);
        } else {
            $user = User::create($request->only('email'));
            $user->assignRole('user');
            Mail::to($request->user())
                ->send(new CreateAcount($user));
            return response()->json(['message' => "You have registered successfully", 'success' => 1, 'status' => 200]);
        }
    }

    public function editUser(UserRequest $request, $id)
    {
        $user = User::find($id);
        if ($user) {
            $user->update($request->only('first_name'));
            return response()->json((['message' => 'User updated', 'success' => 1, 'status' => 200, 'user' => $user]));
        } else {
            return response()->json(['message' => 'erreur! user not found', 'success' => -1, 'status' => 400]);
        }
    }

    public function deleteUser(AdminRequest $request, $id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['message' => 'erreur! user not found', 'success' => -1, 'status' => 400]);
        } else {
            $user->delete();
            return response()->json((['message' => 'User deleted', 'success' => 1, 'status' => 200,]));
        }
    }

    public function AdminUpdateUser(adminRequest $request, $id)
    {
        $user = User::find($id);
        if ($user) {
            $user->update($request->only('email'));

            return response()->json(['message'  => "user updated", 'success' => 1, 'status' => 200, 'user' => $user]);
        } else {
            return response()->json(['message'  => "user not found", 'success' => -1, 'status' => 400]);
        }
    }
}
