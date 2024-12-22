<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SetLocale
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->user()) {
            app()->setLocale($request->user()->language);
        }
        return $next($request);
    }
}
