<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SecurityHeaders
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        $response->headers->set('X-Frame-Options', 'DENY');
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('Referrer-Policy', 'no-referrer-when-downgrade');
        $response->headers->set('Permissions-Policy', 'geolocation=(), microphone=(), camera=()');

        // Content Security Policy – opt-in via env flags
        if (env('ENABLE_CSP', false) || env('ENABLE_CSP_REPORT_ONLY', false)) {
            $self = "'self'";
            $unsafeInline = "'unsafe-inline'"; // allow inline styles/scripts used by many libs
            $unsafeEval = app()->environment('local') ? "'unsafe-eval'" : '';
            $viteWs = app()->environment('local') ? 'ws: http://localhost:5173 ws:' : '';
            $csp = [
                "default-src $self",
                "img-src $self data: https:",
                "font-src $self data: https:",
                "style-src $self $unsafeInline https:",
                "script-src $self $unsafeInline $unsafeEval https:",
                "connect-src $self https: $viteWs",
                "frame-ancestors 'none'",
                "base-uri 'self'",
            ];
            if (env('ENABLE_CSP_REPORT_ONLY', false)) {
                $csp[] = "report-uri ".route('security.csp.report', absolute: false);
            }
            $headerValue = trim(implode('; ', array_filter($csp)));
            $headerName = env('ENABLE_CSP_REPORT_ONLY', false) ? 'Content-Security-Policy-Report-Only' : 'Content-Security-Policy';
            $response->headers->set($headerName, $headerValue);
        }

        if ($request->isSecure()) {
            $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains; preload');
        }

        return $response;
    }
}
