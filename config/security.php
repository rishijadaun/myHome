<?php

return [

    /*
    |--------------------------------------------------------------------------
    | HTTP Strict Transport Security (HSTS)
    |--------------------------------------------------------------------------
    |
    | Enables Strict-Transport-Security header on HTTPS / production requests.
    | Never sent on local development (http://127.0.0.1 or http://localhost).
    |
    */
    'hsts' => [
        'enabled' => env('SECURITY_HSTS_ENABLED', true),
        'max_age' => (int) env('SECURITY_HSTS_MAX_AGE', 31536000), // 1 year
        'include_subdomains' => env('SECURITY_HSTS_INCLUDE_SUBDOMAINS', false),
        'preload' => env('SECURITY_HSTS_PRELOAD', false),
    ],

    /*
    |--------------------------------------------------------------------------
    | Content Security Policy (CSP)
    |--------------------------------------------------------------------------
    |
    | Production-ready Content Security Policy tailored for StayNest.
    | Accommodates Vite, Google Fonts, Leaflet maps, FontAwesome, and Google SSO.
    |
    */
    'csp' => [
        'enabled' => env('SECURITY_CSP_ENABLED', true),
        'report_only' => env('SECURITY_CSP_REPORT_ONLY', false),
        'directives' => [
            "default-src" => ["'self'"],
            "base-uri" => ["'self'"],
            "object-src" => ["'none'"],
            "frame-ancestors" => ["'self'"],
            "form-action" => ["'self'", "https://accounts.google.com", "https://wa.me"],
            "img-src" => ["'self'", "data:", "blob:", "https:"],
            "font-src" => ["'self'", "data:", "https://fonts.gstatic.com", "https://cdnjs.cloudflare.com", "https://fonts.googleapis.com"],
            "style-src" => ["'self'", "'unsafe-inline'", "https://fonts.googleapis.com", "https://cdnjs.cloudflare.com", "https://unpkg.com", "https://cdn.jsdelivr.net"],
            "script-src" => ["'self'", "'unsafe-inline'", "'unsafe-eval'", "https://unpkg.com", "https://cdn.jsdelivr.net", "https://cdnjs.cloudflare.com", "https://maps.googleapis.com"],
            "connect-src" => ["'self'", "https://nominatim.openstreetmap.org", "https://api.bigdatacloud.net", "https://raw.githubusercontent.com", "https://unpkg.com", "https://cdn.jsdelivr.net", "https://images.unsplash.com", "https://accounts.google.com"],
            "frame-src" => ["'self'", "https://accounts.google.com", "https://www.google.com", "https://maps.google.com"],
            "upgrade-insecure-requests" => true,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Core Security Headers
    |--------------------------------------------------------------------------
    */
    'x_content_type_options' => env('SECURITY_X_CONTENT_TYPE_OPTIONS', 'nosniff'),
    'x_frame_options' => env('SECURITY_X_FRAME_OPTIONS', 'SAMEORIGIN'),
    'referrer_policy' => env('SECURITY_REFERRER_POLICY', 'strict-origin-when-cross-origin'),
    'permissions_policy' => env('SECURITY_PERMISSIONS_POLICY', 'camera=(), microphone=(), geolocation=(self), payment=(), usb=()'),
    'x_permitted_cross_domain_policies' => env('SECURITY_X_PERMITTED_CROSS_DOMAIN_POLICIES', 'none'),
    'cross_origin_opener_policy' => env('SECURITY_COOP', 'same-origin-allow-popups'),

    /*
    |--------------------------------------------------------------------------
    | Technology Disclosure Suppression
    |--------------------------------------------------------------------------
    */
    'remove_x_powered_by' => env('SECURITY_REMOVE_X_POWERED_BY', true),

];
