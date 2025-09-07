<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);
        
        // Prevenir clickjacking
        $response->headers->set('X-Frame-Options', 'DENY');
        
        // Prevenir MIME type sniffing
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        
        // Ativar proteção XSS do navegador
        $response->headers->set('X-XSS-Protection', '1; mode=block');
        
        // Forçar HTTPS (apenas em produção)
        if (app()->environment('production')) {
            $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');
        }
        
        // Content Security Policy básico
        $csp = "default-src 'self'; ".
               "script-src 'self' 'unsafe-inline' 'unsafe-eval' https://cdn.jsdelivr.net https://cdnjs.cloudflare.com; ".
               "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com https://cdn.jsdelivr.net https://cdnjs.cloudflare.com; ".
               "font-src 'self' https://fonts.gstatic.com https://cdn.jsdelivr.net; ".
               "img-src 'self' data: https:; ".
               "connect-src 'self'";
        
        $response->headers->set('Content-Security-Policy', $csp);
        
        // Referrer Policy
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        
        // Permissions Policy (Feature Policy)
        $response->headers->set('Permissions-Policy', 'camera=(), microphone=(), geolocation=()');
        
        return $response;
    }
}
