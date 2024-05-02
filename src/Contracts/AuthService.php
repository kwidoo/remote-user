<?php

namespace Kwidoo\RemoteUser\Contracts;

interface AuthService
{
    public function getAccessToken(): string;

    public function retrieveFromApi($identifier = null, $retry = null);
}
