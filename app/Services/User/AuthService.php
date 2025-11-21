<?php

namespace App\Services\User;

use App\Services\Global\AuthService as GlobalAuthService;
use Illuminate\Support\Facades\DB;

class AuthService
{
    protected GlobalAuthService $service;

    public function __construct(GlobalAuthService $service)
    {
        $this->service = $service;
    }

   
    public function register(array $data)
    {
        return DB::transaction(function () use ($data) {

            $customer = $this->service->register($data);

            $token = $customer->createToken('API Token')->accessToken;

            $user = $customer->user;
            $user->access_token = $token;
            $user->token_type   = 'Bearer';

            return $user;
        });
    }

    
    public function login(array $credentials)
    {
        return DB::transaction(function () use ($credentials) {

            $customer = $this->service->login($credentials);

            if (! $customer) {
                return null;
            }

            $token = $customer->createToken('API Token')->accessToken;

            $user = $customer->user;
            $user->access_token = $token;
            $user->token_type   = 'Bearer';

            return $user;
        });
    }

    
    public function logout(): void
    {
        $customer = auth('customer')->user();

        if ($customer && method_exists($customer, 'tokens')) {
            $customer->tokens()->delete();
        }
    }

    
    public function profile()
    {
        $customer = auth('customer')->user();

        return $customer?->user;
    }
}
