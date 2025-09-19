<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SetLocaleFromSession
{
    public function handle(Request $request, Closure $next)
    {
        $locale = session('locale', config('app.locale'));
        app()->setLocale($locale);

        return $next($request);
    }
}
