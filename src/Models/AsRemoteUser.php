<?php

namespace Kwidoo\RemoteUser\Models;

use Illuminate\Support\Facades\Cache;
use Kwidoo\RemoteUser\Contracts\AuthService;
use Sushi\Sushi;

trait AsRemoteUser
{
    use Sushi;

    /**
     * Retrieves the authenticated user from the API using the AuthService.
     *
     * @return array The retrieved user data from the API.
     */
    public function getRows()
    {
        $token = md5(request()->bearerToken());
        return
            Cache::remember('remote-user-' . $token, now()->addMinutes(5), function () {
                $response = app(AuthService::class)->retrieveFromApi();
                return !empty($response) ? [$response] : [];
            });
    }
}
