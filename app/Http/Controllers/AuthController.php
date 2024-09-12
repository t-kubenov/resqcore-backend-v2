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
        $fields = $request->validate([
            "first_name" => "required|max:255",
            "last_name" => "required|max:255",
            "patronymic" => "max:255",
            "email" => "required|email|unique:users",
            "password" => "required|confirmed",
            "role_id" => "required|exists:roles,id"
        ]);

        return $this->authService->register($fields);
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
