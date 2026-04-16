<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\App;

class SetLocale
{
    public function handle(Request $request, Closure $next): Response
    {
        $locale = session('locale', $request->user()?->locale ?? config('app.locale'));

        if (in_array($locale, ['en', 'de'])) {
            App::setLocale($locale);
        }

        return $next($request);
    }
}
