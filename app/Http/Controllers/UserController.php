<?php

namespace App\Http\Controllers;

use App\Http\Requests\AdminRegisterRequest;
use App\Http\Requests\AdminRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\PasswordRequest;
use App\Http\Requests\UserRequest;
use App\Mail\CreateAcount;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Http\Resources\UserCollection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use App\Http\Resources\user as ResourcesUser;
use App\Mail\creatacout;

class UserController extends Controller
{


    public function __construct()
    {

        $this->client = DB::table('oauth_clients')->where('name', 'Laravel Password Grant Client')->first();
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
                    return response()->json(['message'  => "your are welcome", 'success' => 1, 'status' => 200, 'token' => $token, 'role' => $role, 'user' => $user]);
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

            $user = User::create([
                'email'          => $request->email,
                'register_token' => md5(uniqid(rand(), true)),
            ]);
            $user->assignRole('user');
            Mail::to($user->email)
                ->send(new creatacout($user));

            return response()->json(['message' => "You have registered successfully", 'success' => 1, 'status' => 200]);
        }
    }
    public function UserRegister(UserRequest $request)
    {
        $user  = User::where('register_token', $request->token)->first();
        if ($user) {
            $user->update(['first_name' => $request->first_name, 'last_name' => $request->last_name, 'password' => bcrypt($request->password), 'register_token' => null]);
            $user->assignRole('user');
            return response()->json(['message' => "You have registered successfully", 'success' => 1, 'status' => 200]);
        } else {
            return response()->json(['message'  => "user", 'success' => -1, 'status' => 400]);
        }
    }
    public function editUser(UserRequest $request, $id)
    {
        $user = User::find($id);
        if ($user) {
            $user->update($request->only('first_name', 'last_name'));
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
            if (User::where('email', $request->email)->exists()) {
                return response()->json(['message' => 'email exist', 'success' => -1, 'status' => 400]);
            }
            $user->update($request->only('email'));
            return response()->json(['message'  => "user updated", 'success' => 1, 'status' => 200, 'user' => $user]);
        } else {
            return response()->json(['message'  => "user not found", 'success' => -1, 'status' => 400]);
        }
    }
    public function index()
    {
        return response()->json(['message' => "All Users", 'success' => 1, 'status' => 200, 'users' => new UserCollection(User::all())]);
    }


    public function UpdateProfilImage(Request $request)
    {

        $user = User::find(Auth::id());


        $base64_str = preg_replace('/^data:image\/\w+;base64,/', '', $request->image);

        $image = base64_decode($base64_str);
        $type = explode(';', $request->image)[0];
        $type = explode('/', $type)[1]; // png or jpg etc
        $alea = time();
        $url = public_path('/images/') . $alea . $user->id . '.' . $type;
        file_put_contents($url, $image);
        $user->image = $alea . $user->id . '.' . $type;
        $user->save();
        return response()->json(['message' => "Profile image changed successfully", 'succes' => 1, 'status' => 200]);
    }

    public function GetProfilImage(Request $request)
    {
        $user = Auth::user();
        $image = $user->image;
        return response()->json(['message' => "Profil Image", 'success' => 1, 'status' => 200, 'image' => $image]);
    }
    public function show(Request $request)
    {
        $user = $request->user();


        $userResources = new ResourcesUser($user);
        return response()->json(['message'  => "user details", 'success' => 1, 'status' => 200, 'user' => $userResources]);
    }
    public function UserLogout()
    {
        auth()->user()->token()->revoke();
        return response()->json(['message'  => "GoodBye", 'success' => 1, 'status' => 200]);
    }
    public function VerifToken(UserRequest $request)
    {
        $user  = User::where('register_token', $request->token)->first();
        if ($user) {
            return response()->json(['message' => "You have registered successfully", 'success' => 1, 'status' => 200]);
        } else {
            return response()->json(['message' => "Token expired", 'success' => -1, 'status' => 400]);
        }
    }
    public function ChangePassword(Request $request)
    {
        $user = User::find(Auth::id());
        if (Hash::check($request->password, $user->password)) {
            if ($request->new_password === $request->c_new_password) {
                $user->password = Hash::make($request->new_password);
                $user->update();
                return response()->json(['message' => "password updated", 'success' => 1, 'status' => 200]);
            } else
                return response()->json(['message' => "new and confirm new password are wrong", 'success' => -1, 'status' => 400]);
        } else {
            return response()->json(['message' => "old password is wrong", 'success' => -1, 'status' => 400]);
        }
    }
}
