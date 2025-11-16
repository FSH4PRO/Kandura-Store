<?php

namespace App\Services\User;

use App\Services\Global\AuthService as GlobalAuthService;
use Illuminate\Support\Facades\DB;

class AuthService
{
    protected $service;
    public function __construct(GlobalAuthService $service)
    {
        $this->service = $service;
    }

    public function register(array $data)
    {
        return DB::transaction(function () use ($data) {
            // Create user using global service
            $user = $this->service->register($data);

            // Generate API token for Passport
            $token = $user->createToken('API Token')->accessToken;

            // Attach token data to user object
            $user->access_token = $token;
            $user->token_type = 'Bearer';

            return $user;
        });
    }

    public function login(array $credentials)
    {
        return DB::transaction(function () use ($credentials) {
            $user = $this->service->login($credentials);

            if (!$user) {
                return null;
            }

            // Generate API token for Passport
            $token = $user->createToken('API Token')->accessToken;

            // Attach token data to user object
            $user->access_token = $token;
            $user->token_type = 'Bearer';

            return $user;
        });
    }

    /**
     * Logout the user by revoking all API tokens.
     *
     * @return void
     */
    public function logout()
    {
        auth()->user()->tokens()->delete();
    }

    public function profile(){
        return auth()->user();
    }
}
