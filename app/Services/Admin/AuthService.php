<?php

namespace App\Services\Admin;

use Illuminate\Support\Facades\Auth;
use App\Models\Admin;

class AuthService
{
   
   
    public function login(array $credentials, bool $remember = false): ?Admin
    {
       
        $ok = Auth::guard('admin')->attempt([
            'email'    => $credentials['email'] ?? null,
            'password' => $credentials['password'] ?? null,
        ], $remember);

        if (! $ok) {
            return null;
        }

        $admin = Auth::guard('admin')->user();

        if (method_exists($admin, 'hasAnyRole')&& ! $admin->hasAnyRole(['admin', 'super_admin'])) {

            Auth::guard('admin')->logout();
            return null;
        }

        return $admin;
    }

    /**
     * Logout admin.
     */
    public function logout(): void
    {
        Auth::guard('admin')->logout();
    }
}
