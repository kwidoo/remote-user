<?php

namespace Kwidoo\RemoteUser\Models;

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
        return [app(AuthService::class)->retrieveFromApi()];
    }
}
