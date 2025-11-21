<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckAuthenticated
{
    public function handle(Request $request, Closure $next): Response
    {
        // نستخدم guard admin حصراً
        if (! Auth::guard('admin')->check()) {

            if ($request->expectsJson()) {
                return response()->json([
                    'success'   => false,
                    'message'   => "Unauthorized. Please log in.",
                    'data'      => null,
                    'timestamp' => now()->toIso8601String(),
                ], 401);
            }

            return redirect()
                ->route('admin.login')
                ->with('message', 'Please log in to access this page.');
        }

        return $next($request);
    }
}
