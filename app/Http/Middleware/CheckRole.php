<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $admin = Auth::guard('admin')->user(); 

        if (! $admin) {
            return $this->unauthorized($request);
        }

        $actor = $admin->user;

        if (! $actor) {
            return $this->forbidden($request, 'No linked user found for this admin.');
        }

       
        if ($actor->hasRole('super_admin')) {
            return $next($request);
        }

       
        if (empty($roles)) {
            return $next($request);
        }

       
        if (! $actor->hasAnyRole($roles)) {
            return $this->forbidden($request);
        }

        return $next($request);
    }

    private function unauthorized(Request $request)
    {
        if ($request->expectsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Please log in.',
            ], 401);
        }

        return redirect()->route('admin.login');
    }

    private function forbidden(Request $request, string $msg = null)
    {
        if ($request->expectsJson()) {
            return response()->json([
                'success' => false,
                'message' => $msg ?: "Forbidden. You don't have enough permissions.",
            ], 403);
        }

        abort(403, $msg ?: 'Unauthorized action.');
    }
}
