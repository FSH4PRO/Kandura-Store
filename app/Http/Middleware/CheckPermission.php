<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    public function handle(Request $request, Closure $next, string $permission): Response
    {

        $admin = Auth::guard('admin')->user();

        if (! $admin) {
            return $this->unauthorized($request);
        }


        $user = $admin->user;


        if ($user->hasRole('super_admin')) {
            return $next($request);
        }


        if (! $user->can($permission)) {

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => "Forbidden. You don't have permission ({$permission}).",
                ], 403);
            }

            abort(403, 'Unauthorized action.');
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
}
