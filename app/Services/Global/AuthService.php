<?php

namespace App\Services\Global;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AuthService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function register(array $data)
    {
        return DB::transaction(function () use ($data) {
            // Create a new user with the provided data
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'phone' => $data['phone'] ?? null,
                'password' => bcrypt($data['password']),
                'is_active' => true,
            ]);

            if (class_exists(\Spatie\Permission\Models\Role::class)) {
                $user->assignRole('user');
            }

            return $user;
        });
    }

    public function login(array $credentials)
    {
        return DB::transaction(function () use ($credentials) {
            // Find user by email
            $user = User::where('email', $credentials['email'])->first();

            // Check if user exists and password is correct
            if (!$user || !Hash::check($credentials['password'], $user->password)) {
                return null;
            }

            // Check if user is active
            if (!$user->is_active) {
                return null;
            }

            return $user;
        });
    }

    public function logout()
    {
        // Core logout logic
        return true;
    }
}
