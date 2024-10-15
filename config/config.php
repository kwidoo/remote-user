<?php

return [
    // get from Laravel Passport server
    'oauth_url' => env('IAM_URL', '//backend'),
    // 'oauth_client_id' => env('IAM_CLIENT_ID'),
    // 'oauth_client_secret' => env('IAM_CLIENT_SECRET'),
    // 'oauth_scope' => env('IAM_SCOPE', ['*']),

    'user_endpoint' => env('IAM_USER_ENDPOINT', '/api/v1/customer/get'),
    'token_header' => env('IAM_TOKEN_HEADER', 'Authorization'),

    'auth_service_class' => \Kwidoo\RemoteUser\Services\RemoteAuthService::class,
    'user_class' => \App\Models\User::class,
];
