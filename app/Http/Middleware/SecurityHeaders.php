<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    /**
     * Handle an incoming request and apply production-ready HTTP security headers.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        /** @var Response $response */
        $response = $next($request);

        // 1. Remove X-Powered-By Information Disclosure Header
        if (config('security.remove_x_powered_by', true)) {
            if (function_exists('header_remove')) {
                @header_remove('X-Powered-By');
            }
            $response->headers->remove('X-Powered-By');
        }

        // 2. Prevent MIME Type Sniffing
        $contentTypeOptions = config('security.x_content_type_options', 'nosniff');
        if ($contentTypeOptions) {
            $response->headers->set('X-Content-Type-Options', $contentTypeOptions);
        }

        // 3. Clickjacking Protection (X-Frame-Options)
        $frameOptions = config('security.x_frame_options', 'SAMEORIGIN');
        if ($frameOptions) {
            $response->headers->set('X-Frame-Options', $frameOptions);
        }

        // 4. Referrer Policy
        $referrerPolicy = config('security.referrer_policy', 'strict-origin-when-cross-origin');
        if ($referrerPolicy) {
            $response->headers->set('Referrer-Policy', $referrerPolicy);
        }

        // 5. Permissions Policy (Camera & Mic disabled, Geolocation enabled for first-party PG discovery)
        $permissionsPolicy = config('security.permissions_policy', 'camera=(), microphone=(), geolocation=(self), payment=(), usb=()');
        if ($permissionsPolicy) {
            $response->headers->set('Permissions-Policy', $permissionsPolicy);
        }

        // 6. Cross-Domain Policy (Block Flash / PDF cross-domain access)
        $crossDomainPolicies = config('security.x_permitted_cross_domain_policies', 'none');
        if ($crossDomainPolicies) {
            $response->headers->set('X-Permitted-Cross-Domain-Policies', $crossDomainPolicies);
        }

        // 7. Cross-Origin Opener Policy (Allow Google OAuth Popups while isolating browsing context)
        $coop = config('security.cross_origin_opener_policy', 'same-origin-allow-popups');
        if ($coop) {
            $response->headers->set('Cross-Origin-Opener-Policy', $coop);
        }

        // 8. Strict Transport Security (HSTS) - ONLY on HTTPS / Production Requests
        $this->applyHsts($request, $response);

        // 9. Content Security Policy (CSP)
        $this->applyCsp($request, $response);

        return $response;
    }

    /**
     * Apply HSTS header strictly on HTTPS production requests (Never on local HTTP).
     */
    protected function applyHsts(Request $request, Response $response): void
    {
        $isHttps = $request->isSecure()
            || strtolower((string) $request->header('X-Forwarded-Proto', '')) === 'https'
            || strtolower((string) $request->header('X-Forwarded-Ssl', '')) === 'on'
            || strtolower((string) $request->server('HTTPS', '')) === 'on'
            || $request->server('SERVER_PORT') == 443;

        $host = $request->getHost();
        $isLocal = in_array($host, ['localhost', '127.0.0.1', '::1'], true)
            || str_ends_with($host, '.local')
            || str_ends_with($host, '.test');

        if ($isHttps && !$isLocal && config('security.hsts.enabled', true)) {
            $maxAge = (int) config('security.hsts.max_age', 31536000);
            $hstsValue = "max-age={$maxAge}";

            if (config('security.hsts.include_subdomains', false)) {
                $hstsValue .= '; includeSubDomains';
            }

            if (config('security.hsts.preload', false)) {
                $hstsValue .= '; preload';
            }

            $response->headers->set('Strict-Transport-Security', $hstsValue);
        }
    }

    /**
     * Apply tailored Content Security Policy (CSP) header.
     */
    protected function applyCsp(Request $request, Response $response): void
    {
        if (!config('security.csp.enabled', true)) {
            return;
        }

        // Don't duplicate if already set upstream
        if ($response->headers->has('Content-Security-Policy') || $response->headers->has('Content-Security-Policy-Report-Only')) {
            return;
        }

        $directives = config('security.csp.directives', []);
        if (empty($directives)) {
            return;
        }

        $cspParts = [];
        foreach ($directives as $directive => $values) {
            if (is_bool($values)) {
                if ($values) {
                    $cspParts[] = $directive;
                }
            } elseif (is_array($values) && !empty($values)) {
                $cspParts[] = $directive . ' ' . implode(' ', $values);
            } elseif (is_string($values) && !empty($values)) {
                $cspParts[] = $directive . ' ' . $values;
            }
        }

        if (empty($cspParts)) {
            return;
        }

        $cspHeader = implode('; ', $cspParts);
        $headerName = config('security.csp.report_only', false)
            ? 'Content-Security-Policy-Report-Only'
            : 'Content-Security-Policy';

        $response->headers->set($headerName, $cspHeader);
    }
}
