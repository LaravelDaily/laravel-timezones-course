<?php

namespace App\Http\Middleware;

use Carbon\Carbon;
use Closure;
use DateTimeZone;
use Illuminate\Http\Request;

class SetTimezoneMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (auth()->check()) {
            date_default_timezone_set(auth()->user()->timezone);
            $locale = new DateTimeZone(auth()->user()->timezone);
            $localeCode = $locale->getLocation()['country_code'] ?? 'en_US';
            Carbon::setLocale($localeCode);
        }

        return $next($request);
    }
}
