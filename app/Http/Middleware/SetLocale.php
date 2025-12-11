<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SetLocale
{
    public function handle(Request $request, Closure $next)
    {
        // خليك ضامن إن في قيمة افتراضية
        $locale = session('locale', config('app.locale', 'en'));

        if (! in_array($locale, ['ar', 'en'])) {
            $locale = 'en';
        }

        app()->setLocale($locale);

        return $next($request);
    }
}
