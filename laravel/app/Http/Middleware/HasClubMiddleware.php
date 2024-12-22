<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class HasClubMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!$request->user()->club()->exists()) {
            return redirect()->route('choose-club');
        }

        return $next($request);
    }
}
