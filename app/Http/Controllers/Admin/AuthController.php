<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\LoginRequest;
use App\Services\Admin\AuthService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    protected AuthService $service;

    public function __construct(AuthService $service)
    {
        $this->service = $service;
    }

    public function login(LoginRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $remember = isset($validated['remember']) && (bool) $validated['remember'];

        $admin = $this->service->login([
            'email'    => $validated['email'],
            'password' => $validated['password'],
        ], $remember);

        if (! $admin) {
            return back()
                ->withErrors(['email' => 'Invalid credentials or not authorized'])
                ->withInput($request->only('email', 'remember'));
        }

        // Prevent session fixation
        $request->session()->regenerate();

        return redirect()->intended(route('dashboard-analytics'));
    }

    public function showLogin()
    {
        return view('content.authentications.auth-login-basic');
    }

    public function logout(Request $request): RedirectResponse
    {
        
        Auth::guard('admin')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }
}
