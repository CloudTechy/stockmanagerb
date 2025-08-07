<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Cross-Origin Resource Sharing (CORS) Configuration
    |--------------------------------------------------------------------------
    */

    'paths' => ['api/*', 'sanctum/csrf-cookie'],

    'allowed_methods' => ['*'], // Allow all HTTP methods (GET, POST, etc.)

    'allowed_origins' => [
        'https://spacehub-stockmanager.netlify.app',
        'http://localhost:3000',
        'http://127.0.0.1:3000',
        'http://localhost:8080',
    ],

    'allowed_origins_patterns' => [], // Don't use if specific origins are listed

    'allowed_headers' => [
        'Content-Type',
        'X-Requested-With',
        'Authorization',
        'Accept',
        'Origin',
        'DNT',
        'User-Agent',
        'X-Custom-Header',
        'Upgrade-Insecure-Requests',
        'Referer',
    ],

    'exposed_headers' => [
        'Authorization',
        'X-Custom-Header',
    ],

    'max_age' => 3600, // Optional, cache preflight for 1 hour

    'supports_credentials' => true, // Important if you're using Authorization headers
];
