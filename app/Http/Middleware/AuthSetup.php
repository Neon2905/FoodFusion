<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthSetup
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // TODO: Review logic
        // If user is not logged in, just pass the request along
        if (!$request->user()) {
            return $next($request);
        }

        if (!$request->user()->hasVerifiedEmail()) {
            return redirect()->route('verification.notice');
        }

        if (!$request->user()->profile) {
            return redirect()->route('profile.setup');
        }

        return $next($request);
    }
}
