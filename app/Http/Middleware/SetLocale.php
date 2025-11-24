<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class SetLocale
{
    public function handle(Request $request, Closure $next)
    {
        // 1) للـ dashboard: ناخذ من السيشن
        if ($locale = $request->session()->get('app_locale')) {
            App::setLocale($locale);
        }

        // 2) للـ API: لو حابب من هيدر أو query مثل ?lang=ar
        if ($request->has('lang')) {
            App::setLocale($request->get('lang'));
        } elseif ($headerLocale = $request->header('Accept-Language')) {
            // مثلاً لو جاك "ar" أو "en-US" الخ...
            $short = substr($headerLocale, 0, 2);
            if (in_array($short, ['ar', 'en'])) {
                App::setLocale($short);
            }
        }

        return $next($request);
    }
}
