<?php

namespace App\Services\System;

use App\Models\System\User;
use Carbon\Carbon;
use Laravel\Sanctum\NewAccessToken;
use function config;

class AuthService
{
    public function createAccessToken(User $user): NewAccessToken
    {
        $token = $user->createToken(
            name: 'authToken',
            expiresAt: Carbon::now()->addMinutes(config('sanctum.expiration'))
        );
        return $token;
    }
}
