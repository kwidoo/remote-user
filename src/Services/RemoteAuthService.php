<?php

namespace Kwidoo\RemoteUser\Services;

use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Kwidoo\RemoteUser\Contracts\AuthService;
use Kwidoo\RemoteUser\Exceptions\RemoteAuthorizationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class RemoteAuthService implements AuthService
{
    /**
     * Get access token from remote Laravel Passport server. This will obtain client credentials grant.
     *
     * @return string
     */
    public function getAccessToken(): string
    {
        return Cache::remember('access_token', 3000, function () {
            $response = Http::post(config('iam.oauth_url') . config('iam.oauth_endpoint'), [
                'grant_type' => 'client_credentials',
                'client_id' => config('iam.oauth_client_id'),
                'client_secret' => config('iam.oauth_client_secret'),
                'scope' => config('iam.oauth_scope', ['*']),
            ]);

            if ($response->successful()) {
                return $response->json('access_token');
            }

            throw new RemoteAuthorizationException('Failed to retrieve access token');
        });
    }

    /**
     * Retrieve user data from the remote service. Since this relies on an external service, the
     * $identifier is fetched from the frontend request header: config('iam.token_header', 'X-IAM-Token').
     * In the console or where the request header is not available, the $identifier can be passed as a parameter.
     *
     * @param mixed $identifier
     * @param mixed $retry
     * @return array
     * @throws HttpException
     * @throws NotFoundHttpException
     * @throws HttpResponseException
     * @throws ConnectionException
     */
    public function retrieveFromApi($identifier = null, $retry = null)
    {
        $token = $this->getAccessToken();
        $iamToken = request()->header(config('iam.token_header'), $identifier);
        if (!$token || !$iamToken) {
            return abort(403);
        }

        // Fetch user data from IAM server using the opaque token
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token,
            config('iam.token_header') => $iamToken,
            'Accept' => 'application/json',
        ])->get(config('iam.oauth_url') . config('iam.user_endpoint'));

        if ($response->successful()) {
            return $response->json();
        }
        if (!$retry) {
            Cache::forget('access_token');
            return $this->retrieveFromApi($identifier, true);
        }

        return [];
    }
}
