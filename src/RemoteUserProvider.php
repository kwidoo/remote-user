<?php

namespace Kwidoo\RemoteUser;

use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Contracts\Auth\Authenticatable;
use Kwidoo\RemoteUser\Contracts\AuthService;
use Kwidoo\RemoteUser\Contracts\RemoteUser;

class RemoteUserProvider implements UserProvider
{
    public function __construct(protected AuthService $authService)
    {
        //
    }

    public function retrieveById($identifier)
    {
        $data = $this->authService->retrieveFromApi($identifier);

        if ($data) {
            return app(RemoteUser::class, [$data]);
        }

        return null;
    }

    public function retrieveByToken($identifier, $token)
    {
        return $this->retrieveById($token);
    }

    public function updateRememberToken(Authenticatable $user, $token)
    {
        // No operation
    }

    public function retrieveByCredentials(array $credentials)
    {
        // Implementation depends on requirements
    }

    public function validateCredentials(Authenticatable $user, array $credentials)
    {
        // Validation handled by IAM server
        return true;
    }

    /** @SuppressWarnings(PHPMD) */
    public function rehashPasswordIfRequired(Authenticatable $user, array $credentials, bool $force = false)
    {
        //
    }
}
