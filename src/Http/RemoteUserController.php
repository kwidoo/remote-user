<?php

namespace Kwidoo\RemoteUser\Http;

use App\Http\Controllers\Controller;
use Kwidoo\RemoteUser\Contracts\RemoteUser;

class RemoteUserController extends Controller
{
    /**
     * Get Sanctum access token for the remote user.
     *
     * @return array
     */
    public function token()
    {
        return [
            'access_token' =>
            app(RemoteUser::class)::first()
                ->createToken(
                    md5(now()->toString())
                )->plainTextToken,
        ];
    }
}
