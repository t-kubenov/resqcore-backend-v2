<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    public function getUser($user)
    {
        $this->loadRelationships($user);

        return $user;
    }

    public function register(array $fields)
    {
        $user = User::create($fields);

        $token = $user->createToken($fields['first_name']);

        $this->loadRelationships($user);

        return [
            "user" => $user,
            "token" => $token->plainTextToken
        ];
    }

    public function login(array $credentials)
    {
        $user = User::where("email", $credentials['email'])->first();

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            return response()->json([
                "error" => "The provided credentials are incorrect.",
            ], 422); // 422 is the status code for unprocessable entity
        }

        $token = $user->createToken($user->first_name);

        $this->loadRelationships($user);

        return [
            "user" => $user,
            "token" => $token->plainTextToken
        ];
    }

    public function loadRelationships($user)
    {
        $user->load(['role', 'branch_office', 'company']);

        $user->role?->makeHidden(['created_at', 'updated_at']);
        $user->company?->makeHidden(['created_at', 'updated_at']);
        $user->branch_office?->makeHidden(['created_at', 'updated_at']);
    }

    public function logout($user)
    {
        $user->tokens()->delete();

        return ["message" => "You are logged out."];
    }
}
