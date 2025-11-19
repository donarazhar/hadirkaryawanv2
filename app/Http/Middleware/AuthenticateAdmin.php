<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class AuthenticateAdmin
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        Log::info('AuthenticateAdmin Middleware', [
            'authenticated' => Auth::guard('admin')->check(),
            'url' => $request->fullUrl()
        ]);

        // Check if authenticated with admin guard
        if (!Auth::guard('admin')->check()) {
            Log::warning('Admin not authenticated');

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized.'
                ], 401);
            }

            return redirect()->route('admin.login')
                ->with('error', 'Silakan login sebagai admin terlebih dahulu.');
        }

        // Check if user is actually an admin
        $user = Auth::guard('admin')->user();
        if (!$this->isAdmin($user)) {
            Log::warning('User is not admin', [
                'user_id' => $user->id ?? 'unknown',
                'role' => $user->role ?? 'no role'
            ]);

            Auth::guard('admin')->logout();

            return redirect()->route('admin.login')
                ->with('error', 'Anda tidak memiliki akses sebagai administrator.');
        }

        Log::info('Admin authenticated', [
            'user_id' => $user->id,
            'role' => $user->role
        ]);

        return $next($request);
    }

    /**
     * Check if user is admin
     */
    protected function isAdmin($user): bool
    {
        return isset($user->role) && in_array($user->role, ['super_admin', 'admin', 'pimpinan']);
    }
}
