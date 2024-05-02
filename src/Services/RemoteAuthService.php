<?php

namespace Kwidoo\RemoteUser\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Kwidoo\RemoteUser\Contracts\AuthService;
use Kwidoo\RemoteUser\Exceptions\RemoteAuthorizationException;

class RemoteAuthService implements AuthService
{
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
