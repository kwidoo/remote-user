<?php

namespace Kwidoo\RemoteUser\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Sushi\Sushi;

class PersonalAccessToken extends Model
{
    use HasFactory;
    use Sushi;

    protected $guarded = [];

    /**
     * @return MorphTo
     */
    public function tokenable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Retrieves the authenticated user from the API using the AuthService.
     *
     * @return array The retrieved user data from the API.
     */
    public function getRows()
    {
        return [[
            'tokenable_type' => config('iam.user_class'),
            'tokenable_id' => config('iam.user_class')::first()->id,
            'name' => 'CustomToken',
            'abilities' => '',
            'created_at' => now()->subMinute(),
            'last_used_at' => now(),
        ]];
    }

    /**
     * @param mixed $token
     *
     * @return self
     */
    public static function findToken($token)
    {
        return self::first();
    }
}
