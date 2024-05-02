<?php

return [
    // get from Laravel Passport server
    'oauth_url' => env('IAM_URL'),
    'oauth_client_id' => env('IAM_CLIENT_ID'),
    'oauth_client_secret' => env('IAM_CLIENT_SECRET'),
    'oauth_scope' => env('IAM_SCOPE', ['*']),

    'user_endpoint' => env('IAM_USER_ENDPOINT', '/e/user'),
    'token_header' => env('X-IAM-Token', 'X-IAM-Token'),
    'oauth_endpoint' => env('IAM_OAUTH_ENDPOINT', '/oauth/token/'),

    'auth_service_class' => \Kwidoo\RemoteUser\Services\RemoteAuthService::class,
];
