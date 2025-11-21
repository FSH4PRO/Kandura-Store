<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Response;

class SetLocale
{
     public function handle(Request $request, Closure $next): Response
    {
        $supportedLanguages = ['en', 'ar'];
        
        $locale = session('locale', config('app.locale', 'en'));
        
        if (!in_array($locale, $supportedLanguages)) {
            $locale = 'en';
        }
        
        App::setLocale($locale);
        
        return $next($request);
    }
}
