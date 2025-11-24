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
    protected AuthService $service;

    public function __construct(AuthService $service)
    {
        $this->service = $service;
    }

    public function register(RegisterRequest $request)
    {

        $user = $this->service->register($request->validated());

        return $this->success(
            new UserResource($user),
             __('messages.user_registered'),
            201
        );
    }

    public function login(LoginRequest $request)
    {
        $user = $this->service->login($request->validated());

        if (! $user) {
            return $this->failed('Invalid credentials', null, 401);
        }

        return $this->success(
            new UserResource($user),
             __('messages.user_logged_in'),
            200
        );
    }

    public function logout(Request $request)
    {
        $this->service->logout();

        return $this->success(null,  __('messages.user_logged_out'), 200);
    }

   
}
