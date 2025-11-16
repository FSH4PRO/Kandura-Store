<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        // نفترض إنو CheckAuthenticated مر قبله
        $user = Auth::user(); // أو $request->user()

        // لو صار أي خلل وما في يوزر، منعتبرها forbidden
        if (! $user) {
            // API
            if ($request->expectsJson()) {
                return response()->json([
                    'success'   => false,
                    'message'   => 'forbidden. unauthorized access.', 
                    'data'      => null,
                    'timestamp' => now()->toIso8601String(),
                ], 403);
            }

            // Web
            abort(403, 'Unauthorized action.');
        }

        // هون بس منشيك على الأدوار (مسؤولية هاد الميدلواير)
        if (method_exists($user, 'hasAnyRole') && ! $user->hasAnyRole($roles)) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success'   => false,
                    'message'   => 'forbidden. insufficient role permissions.',
                    'data'      => null,
                    'timestamp' => now()->toIso8601String(),
                ], 403);
            }

            abort(403, 'Unauthorized action.');
        }

        return $next($request);
    }
}
