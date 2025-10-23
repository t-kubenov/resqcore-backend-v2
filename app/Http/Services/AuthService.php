<?php

namespace App\Http\Services;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class AuthService
{
    public function getUser()
    {
        $user = auth()->user();

        return new UserResource($user);
    }

    public function register(array $fields)
    {
        $user = User::create($fields);

        $user->assignRole($fields['role_id']);

        $token = $user->createToken($fields['first_name']);

        return new UserResource($user, $token->plainTextToken);
    }

    public function login(array $credentials)
    {
        $user = User::whereRaw('LOWER(email) = ?', [strtolower($credentials['email'])])->first();

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            return response()->json([
                "message" => __("auth.password"),
            ], 422); // 422 is the status code for unprocessable entity
        }

        $token = $user->createToken($user->first_name);

        return new UserResource($user, $token->plainTextToken);
    }

    public function changePassword($validated)
    {
        $user = auth()->user();

        if (!Hash::check($validated['old_password'], $user->password)) {
            return response()->json([
                "message" => __("passwords.old"),
            ], 422);
        }

        $user->password = Hash::make($validated['new_password']);

        $user->save();

        return ["message" => __("passwords.changed")];

    }

    public function validatePassword($password) // used in the controller
    {
        $password = Validator::make(["password" => $password], [
            'password' => ['required', Password::min(8)->numbers()->letters()->mixedCase()->symbols()],
        ]);

        return $password->validated()['password'];
    }

    public function logout($user)
    {
        $user->currentAccessToken()->delete();

        return ["message" => __("auth.logout")];
    }

}
