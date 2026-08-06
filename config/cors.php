<?php

return [
    'paths' => ['api/*', 'sanctum/csrf-cookie'],
    'allowed_methods' => ['*'],

    // Comma-separated list of allowed frontend origins, e.g.
    //   FRONTEND_URLS=https://app.kandurastore.com,https://admin.kandurastore.com
    // Falls back to common local dev ports (Vite defaults) when unset, so
    // local development keeps working out of the box. This was previously
    // hardcoded to only localhost:5173/5174, which silently dropped every
    // request from a deployed frontend origin (production CORS failure).
    'allowed_origins' => array_values(array_filter(array_map(
        'trim',
        explode(',', env('FRONTEND_URLS', 'http://localhost:5173,http://localhost:5174'))
    ))),

    'allowed_origins_patterns' => [],
    'allowed_headers' => ['*'],
    'exposed_headers' => [],
    'max_age' => 0,
    'supports_credentials' => true,
];
