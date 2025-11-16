<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\RegisterRequest;
use App\Http\Requests\User\LoginRequest;
use App\Http\Resources\UserResource;
use App\Services\User\AuthService;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    protected $service;
    public function __construct(AuthService $service)
    {
        $this->service = $service;
    }

    public function register(RegisterRequest $request)
    {
        $user = $this->service->register($request->validated());

        return $this->success(new UserResource($user), 'User registered successfully', 201);
    }

    public function login(LoginRequest $request)
    {
        $user = $this->service->login($request->validated());

        if (! $user) {
            return $this->failed('Invalid credentials', null, 401);
        }

        return $this->success(new UserResource($user), 'User logged in successfully', 200);
    }

    /**
     * Logout the user.
     */
    public function logout(Request $request)
    {
        $this->service->logout();

        return $this->success(null, 'User logged out successfully', 200);
    }

    public function profile(){
        $user = $this->service->profile();
        return $this->success(new UserResource($user), 'User profile', 200);
    }
}