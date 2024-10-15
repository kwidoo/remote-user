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
        $iamToken = request()->header(config('iam.token_header'), $identifier);
        if (!$iamToken) {
            throw new RemoteAuthorizationException('Failed to retrieve user data');
        }

        // Fetch user data from IAM server using the opaque token
        return Cache::remember('remote-user-' . md5($iamToken), now()->addMinutes(5), function () use ($iamToken, $identifier) {
            $response =  Http::withHeaders([
                'Authorization' => 'Bearer ' . $iamToken,
                'Accept' => 'application/json',
            ])->get(config('iam.oauth_url') . config('iam.user_endpoint'));

            if ($response->successful()) {
                return $response->json('data');
            }

            throw new RemoteAuthorizationException('Failed to retrieve user data');
        });
    }
}
