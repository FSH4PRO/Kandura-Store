<?php

namespace App\Services\Admin;

use App\Services\Global\AuthService as GlobalAuthService;
use Illuminate\Support\Facades\Auth;

class AuthService
{
    protected $service;
    public function __construct(GlobalAuthService $service)
    {
        $this->service = $service;
    }

    /**
     * Login admin user via session (web).
     *
     * @param array $credentials
     * @param bool $remember
     * @return \App\Models\User|null
     */
    public function login(array $credentials, bool $remember = false)
    {
        $user = $this->service->login($credentials);

        if (!$user) {
            return null;
        }

        // If the app uses roles (spatie), ensure the user is an admin
        if (method_exists($user, 'hasRole')) {
            if (! $user->hasAnyRole(['admin', 'super_admin'])) {
                return null;
            }
        }


        // Log the user into the session for web access
        Auth::login($user, $remember);

        return $user;
    }

    /**
     * Logout the admin user.
     *
     * @return void
     */
    public function logout()
    {
        $this->service->logout();
        Auth::logout();
    }
}
