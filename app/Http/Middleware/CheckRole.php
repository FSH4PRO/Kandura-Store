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
        // التأكد من أنّ المستخدم داخل عبر admin guard
        $user = Auth::guard('admin')->user();

        if (! $user) {
            return $this->unauthorized($request);
        }

        // super admin عنده كامل الصلاحيات
        if ($user->hasRole('super_admin')) {
            return $next($request);
        }

        // التحقق من باقي الأدوار
        if (! $user->hasAnyRole($roles)) {

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => "Forbidden. You don't have enough permissions.",
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
