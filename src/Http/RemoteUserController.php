<?php

namespace Kwidoo\RemoteUser\Http;

use App\Http\Controllers\Controller;
use Kwidoo\RemoteUser\Contracts\RemoteUser;

class RemoteUserController extends Controller
{
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
