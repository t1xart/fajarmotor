<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SecurityHeaders
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // ── Anti Clickjacking ──
        $response->headers->set('X-Frame-Options', 'DENY');

        // ── Anti XSS (browser built-in) ──
        $response->headers->set('X-XSS-Protection', '1; mode=block');

        // ── Cegah MIME sniffing ──
        $response->headers->set('X-Content-Type-Options', 'nosniff');

        // ── Referrer Policy ──
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');

        // ── Content Security Policy — blokir inline scripts berbahaya ──
        $response->headers->set('Content-Security-Policy',
            "default-src 'self'; ".
            "script-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net https://code.jquery.com https://cdnjs.cloudflare.com https://cdn.datatables.net; ".
            "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com https://cdn.jsdelivr.net https://cdnjs.cloudflare.com https://cdn.datatables.net; ".
            "font-src 'self' https://fonts.gstatic.com https://cdnjs.cloudflare.com; ".
            "img-src 'self' data: https://images.unsplash.com https://cdn.jsdelivr.net; ".
            "connect-src 'self'; ".
            "frame-ancestors 'none';"
        );

        // ── HSTS — paksa HTTPS (hanya di production) ──
        if (app()->isProduction()) {
            $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');
        }

        // ── Permissions Policy — batasi akses fitur browser ──
        $response->headers->set('Permissions-Policy', 'camera=(), microphone=(), geolocation=(), payment=()');

        // ── Hapus header yang membocorkan info server ──
        $response->headers->remove('X-Powered-By');
        $response->headers->remove('Server');

        return $response;
    }
}
