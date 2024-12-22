<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class NoClubMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->user()->club()->exists()) {
            return redirect()->route('dashboard');
        }

        return $next($request);
    }
}
