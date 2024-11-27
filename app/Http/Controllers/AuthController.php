<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\AuthService;

class AuthController extends Controller
{
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            "first_name" => "required|max:255",
            "last_name" => "required|max:255",
            "patronymic" => "max:255",
            "email" => "required|email|unique:users",
            "password" => "required|confirmed",
            "role_id" => "required|exists:roles,id",
            "branch_id" => "required_if:role_id,3|exists:branch_offices,id",
            "position" => "sometimes|max:255"
        ]);

        $validated['password'] = $this->authService->validatePassword($validated['password']);

        return $this->authService->register($validated);
    }


    public function changePassword(Request $request)
    {
        $validated = $request->validate([
            "old_password" => "required",
            "new_password" => "required|confirmed",
        ]);

        $validated['new_password'] = $this->authService->validatePassword($validated['new_password']);

        return $this->authService->changePassword($validated);
    }

    public function uploadAvatar(Request $request)
    {
        $request->validate([
            "avatar" => "required|image|mimes:jpeg,png,jpg,svg,webp|max:2048"
        ]);

        return $this->authService->uploadAvatar($request->file('avatar'));
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            "email" => "required|email",
            "password" => "required"
        ]);

        return $this->authService->login($credentials);
    }

    public function user()
    {
        return $this->authService->getUser();
    }

    public function logout(Request $request)
    {
        return $this->authService->logout($request->user());
    }
}
