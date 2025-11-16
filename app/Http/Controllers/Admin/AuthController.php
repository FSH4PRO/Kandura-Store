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
    protected $service;

    public function __construct(AuthService $service)
    {
        $this->service = $service;
    }

    /**
     * Handle admin web login.
     */
    public function login(LoginRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $remember = isset($validated['remember']) && (bool) $validated['remember'];

        $user = $this->service->login([
            'email' => $validated['email'],
            'password' => $validated['password'],
        ], $remember);

        if (! $user) {
            return back()
                ->withErrors(['email' => 'Invalid credentials or not authorized'])
                ->withInput($request->only('email', 'remember'));
        }

        // Prevent session fixation
        $request->session()->regenerate();

        // Redirect to intended URL or main dashboard
        return redirect()->intended(route('dashboard-analytics'));
    }

    /**
     * Show the admin login page.
     */
    public function showLogin()
    {
        return view('content.authentications.auth-login-basic');
    }

    /**
     * Handle admin logout.
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }
}
