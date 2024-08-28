<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;



class AuthController extends Controller
{

    public function register(Request $request)
    {
        $fields = $request->validate([
            "first_name" => "required|max:255",
            "last_name" => "required|max:255",
            "patronymic" => "max:255",
            "email" => "required|email|unique:users",
            "password" => "required|confirmed",
            "role_id" => "required|exists:roles,id"
        ]);

        $user = User::create($fields);

        $token = $user->createToken($request->first_name);

        return [
            "user" => $user,
            "token" => $token->plainTextToken
        ];
    }

    public function login(Request $request)
    {

        // $messages = [
        //     'email.required' => 'The provided credentials are incorrect.',
        //     'email.email' => 'The provided credentials are incorrect.',
        //     'email.exists' => 'The provided credentials are incorrect.',
        //     'password.required' => 'The provided credentials are incorrect.',
        // ];

        $request->validate([
            "email" => "required|email",
            "password" => "required"
        ]);

        $user = User::where("email", $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password))
        {
            return response()->json([
                "error" => "The provided credentials are incorrect.",
            ], 422); // 422 is the status code for unprocessable entity
        }

        $token = $user->createToken($user->first_name);

        return [
            "user" => $user,
            "token" => $token->plainTextToken
        ];
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return ["message" => "You are logged out."];
    }
}
