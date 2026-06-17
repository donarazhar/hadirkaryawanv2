<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware untuk mencegah browser/PWA meng-cache halaman panel admin.
 * Ini penting agar Service Worker PWA karyawan tidak menyimpan
 * halaman panel admin di cache-nya.
 */
class NoCachePanelMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $response->headers->set('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');
        $response->headers->set('Pragma', 'no-cache');
        $response->headers->set('Expires', '0');
        // Beritahu browser bahwa ini bukan halaman PWA
        $response->headers->set('X-PWA-Bypass', 'true');

        return $response;
    }
}
