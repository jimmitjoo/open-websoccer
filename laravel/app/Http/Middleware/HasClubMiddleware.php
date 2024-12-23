<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class HasClubMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!$request->user() || !$request->user()->club) {
            return redirect()->route('choose-club')
                ->with('error', 'Du måste ha en klubb för att komma åt denna sida.');
        }

        return $next($request);
    }
}
