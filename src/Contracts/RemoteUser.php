<?php

namespace Kwidoo\RemoteUser\Contracts;

interface RemoteUser
{
    /**
     * The package uses the Sushi package to provide a local cache of the remote user data.
     *
     * @return mixed
     */
    public function getRows();
}
