<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class Authenticate
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string $guard = 'karyawan'): Response
    {
        // Log untuk debugging
        Log::info('Authenticate Middleware', [
            'guard' => $guard,
            'authenticated' => Auth::guard($guard)->check(),
            'url' => $request->fullUrl()
        ]);

        // Check if user is authenticated with the specified guard
        if (!Auth::guard($guard)->check()) {
            Log::warning('User not authenticated', [
                'guard' => $guard,
                'url' => $request->fullUrl()
            ]);

            // If AJAX request, return JSON response
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthenticated.',
                    'redirect' => route('login')
                ], 401);
            }

            // Store intended URL for redirect after login
            if (!$request->is('login') && !$request->is('proseslogin') && !$request->is('/')) {
                session()->put('url.intended', $request->fullUrl());
            }

            // Redirect to login with flash message
            return redirect()->route('login')
                ->with('error', 'Silakan login terlebih dahulu.');
        }

        // User is authenticated, continue
        Log::info('User authenticated', [
            'guard' => $guard,
            'user' => Auth::guard($guard)->user()->nik ?? 'unknown'
        ]);

        return $next($request);
    }
}
