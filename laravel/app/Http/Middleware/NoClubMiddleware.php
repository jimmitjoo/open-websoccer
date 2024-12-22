<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class NoClubMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->user() && $request->user()->club) {
            return redirect()->route('dashboard')
                ->with('error', 'Du har redan en klubb.');
        }

        return $next($request);
    }
}
