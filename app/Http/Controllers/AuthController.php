<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\RegisterRequest;

class AuthController extends Controller
{
    //
    public function Register(RegisterRequest $request)
    {
        $new_user = array(
            "name" => $request->name,
            "email" => $request->email,
            "password" => $request->password,
            "confirm_password" => $request->confirm_password,
        );
        $add_user = User::create($new_user);

        return response()->json(["message" => 'Registered Successfully', 'user' => $add_user,], 200);
    }

    public function Login(LoginRequest $request)
    {
        $user = User::where('email', $request->email)->first();
        // if ($user) {
        //     error_log('User found: ' . print_r($user->toArray(), true));
        // } else {
        //     error_log('No user found with the email: ' . $request->email);
        // }
        if ($user) {
            if (password_verify($request->password, $user->password)) {
                $token = $user->createToken('login_token')->accessToken;
                return response()->json(["message" => 'Logged in Successfully', 'user' => $user, 'token' => $token], 200);
            } else {
                return response()->json(["message" => 'User not found!'], 404);
            }

        } else {
            return response()->json(["message" => 'User not found!'], 404);
        }
    }
}
