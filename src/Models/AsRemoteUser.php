<?php

namespace Kwidoo\RemoteUser\Models;

use Kwidoo\RemoteUser\Contracts\AuthService;
use Sushi\Sushi;

trait AsRemoteUser
{
    use Sushi;

    public function getRows()
    {
        return [app(AuthService::class)->retrieveFromApi()];
    }
}
