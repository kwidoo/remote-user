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

    /**
     * @param mixed $identifier
     *
     * @return RemoteUser|null
     */
    public function retrieveById($identifier)
    {
        $data = $this->authService->retrieveFromApi($identifier);

        if ($data) {
            return app(RemoteUser::class, [$data]);
        }

        return null;
    }

    /// For compatibility only

    /** @SuppressWarnings(PHPMD) */
    public function retrieveByToken($identifier, $token)
    {
        return $this->retrieveById($identifier);
    }

    /** @SuppressWarnings(PHPMD) */
    public function updateRememberToken(Authenticatable $user, $token)
    {
        // No operation
    }

    /** @SuppressWarnings(PHPMD) */
    public function retrieveByCredentials(array $credentials)
    {
        // Implementation depends on requirements
    }

    /** @SuppressWarnings(PHPMD) */
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
