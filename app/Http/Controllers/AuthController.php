<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{

    public function register(Request $request)
    {
        $fields = $request->validate([
            "name" => "required|max:255",
            "email" => "required|email|unique:users",
            "password" => "required|confirmed"
        ]);

        $user = User::create($fields);

        $token = $user->createToken($request->name);

        return [
            "user" => $user,
            "token" => $token->plainTextToken
        ];
    }

    public function login(Request $request)
    {

        $messages = [
            'email.required' => 'The provided credentials are incorrect.',
            'email.email' => 'The provided credentials are incorrect.',
            'email.exists' => 'The provided credentials are incorrect.',
            'password.required' => 'The provided credentials are incorrect.',
        ];

        $request->validate([
            "email" => "required|email|exists:users",
            "password" => "required"
        ], $messages);

        $user = User::where("email", $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password))
        {
            return response()->json([
                "error" => "The provided credentials are incorrect."
            ], 422); // 401 Unauthorized
        }

        $token = $user->createToken($user->name);

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
