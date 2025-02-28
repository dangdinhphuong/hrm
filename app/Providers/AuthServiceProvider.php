<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use App\Models\System\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Str;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        Auth::viaRequest('api-key', function (Request $request) {
            $authorizationHeader = $request->header('Authorization');

            if (Str::startsWith($authorizationHeader, 'ApiKey ')) {
                $apiKey = substr($authorizationHeader, 7);
                if ($user = User::where('api_key', $apiKey)->first()) {
                    return $user;
                }
            }

            throw new AuthenticationException;
        });
    }
}
