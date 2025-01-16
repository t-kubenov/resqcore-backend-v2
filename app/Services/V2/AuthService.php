<?php

namespace App\Services\V2;

use App\Http\Resources\V2\UserResource;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class AuthService
{
    public function getUser()
    {
        $user = auth()->user();

        $this->loadRelationships($user);

        return new UserResource($user);
    }

    public function register(array $fields)
    {
        $user = User::create($fields);

        $user->assignRole($fields['role_id']);

        $token = $user->createToken($fields['first_name']);

        $this->loadRelationships($user);

        return new UserResource($user, $token->plainTextToken);
    }

    public function login(array $credentials)
    {
        $user = User::where("email", $credentials['email'])->first();

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            return response()->json([
                "message" => __("auth.password"),
            ], 422); // 422 is the status code for unprocessable entity
        }

        $token = $user->createToken($user->first_name);

        $this->loadRelationships($user);

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

    public function uploadAvatar($avatar)
    {
        $user = auth()->user();

        if (!$avatar) {
            return ["message" => __("auth.avatar.upload_failure")];
        }

        if ($user->avatar) {
            Storage::disk('public')->delete($user->avatar);
        }

        $user->avatar = $avatar? $avatar->store('avatars', 'public'): null;

        $user->save();

        return ["message" => __("auth.avatar.upload_success")];
    }

    public function validatePassword($password) // used in the controller
    {
        $password = Validator::make(["password" => $password], [
            'password' => ['required', Password::min(8)->numbers()->letters()->mixedCase()->symbols()],
        ]);

        return $password->validated()['password'];
    }

    public function loadRelationships($user)
    {
        $user->load([
            'role' => function ($query) {
                $query->select('id', 'name');
            },
            'branch_office' => function ($query) {
                $query->select('id', 'name');
            },
            'company' => function ($query) {
                $query->select('id', 'name');
            },
            'role.permissions' => function ($query) {
                $query->select('id', 'name');
            },
        ]);

        $user->role?->permissions->makeHidden('pivot');
    }

    public function logout($user)
    {
        $user->currentAccessToken()->delete();

        return ["message" => __("auth.logout")];
    }

}
