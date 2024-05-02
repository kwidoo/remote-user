<?php

namespace Kwidoo\RemoteUser\Contracts;

interface AuthService
{
    /**
     * Get access token from remote Laravel Passport server. This will obtain client credentials grant.
     *
     * @return string The access token.
     */
    public function getAccessToken(): string;

    /**
     * Retrieve user data from the remote service. Since this relies on an external service, the
     * $identifier is fetched from the frontend request header: config('iam.token_header', 'X-IAM-Token').
     * In the console or where the request header is not available, the $identifier can be passed as a parameter.
     *
     * @param mixed $identifier The identifier used to retrieve user data. Defaults to null when fetched from the request header.
     * @param mixed $retry      Indicates wether request is called for a first time or it is retry call. Will try once, then will throw error
     *
     * @return array The retrieved user data.
     */
    public function retrieveFromApi($identifier = null, $retry = null);
}
