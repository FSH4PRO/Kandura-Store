<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SetLocale
{
    public function handle(Request $request, Closure $next)
    {
        // جرب بالترتيب: query ?lang= / header / default
        $locale = $request->query('lang')
            ?? $request->header('Accept-Language')
            ?? config('app.locale');

        $available = config('app.available_locales', ['en', 'ar']);

        if (! in_array($locale, $available)) {
            $locale = config('app.locale');
        }

        app()->setLocale($locale);

        return $next($request);
    }
}
