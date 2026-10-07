<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    /**
     * Add baseline security headers to every web response.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Block clickjacking (e.g. hidden iframes over the admin panel)
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
        // Stop browsers from MIME-sniffing uploaded files into scripts
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        // Don't leak full URLs (order IDs, reset tokens) to third-party sites
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        // Geolocation is needed by checkout/driver tracking; disable the rest
        $response->headers->set('Permissions-Policy', 'geolocation=(self), camera=(), microphone=(), payment=()');

        // Force HTTPS for returning visitors, only once the site is actually served over HTTPS
        if ($request->secure() && app()->isProduction()) {
            $response->headers->set('Strict-Transport-Security', 'max-age=31536000');
        }

        return $response;
    }
}
