<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class TestCookie
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        $response->headers->set(
            'Set-Cookie',
            'test_cookie=hello; Path=/; Secure; HttpOnly; SameSite=Lax'
        );

        $response->headers->set(
            'X-Test-Cookie',
            'YES'
        );

        return $response;
    }
}