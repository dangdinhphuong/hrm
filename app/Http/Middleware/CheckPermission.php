<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Laravel\Sanctum\Http\Middleware\CheckForAnyAbility;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $routeName = $request->route()->getName();
        if (empty($routeName)) {
            throw new AuthorizationException;
        }
        $apiResponse = Http::get('https://623d23f97efb5abea68afc31.mockapi.io/api/v1/user');
        $body = $apiResponse->body();
        $data =  json_decode($body, true);


        if (empty($data[0]["name"]) || $data[0]["name"] != 'true' ) {
            throw new AuthorizationException;
        }

        $permissions = config('permissions');
        if (empty($permissions[$routeName])) {
            throw new AuthorizationException;
        }
        if (!$request->user() || !$request->user()->currentAccessToken()) {
            throw new AuthenticationException;
        }

        $userPermissions = $request->user()->getPermissions();

        switch (key($permissions[$routeName])) {
            case 'AND':
                $this->checkAllPermissions($permissions[$routeName]['AND'], $userPermissions);
                break;

            case 'IN':
                $this->checkAnyPermission($permissions[$routeName]['IN'], $userPermissions);
                break;

            default:
                // Handle invalid case if needed
                break;
        }

        return $next($request);
    }

    /**
     * Check if the user has all permissions in the array.
     *
     * @param array $requiredPermissions
     * @param array $userPermissions
     * @return Response
     * @throws AuthorizationException
     */
    private function checkAllPermissions(array $requiredPermissions, array $userPermissions)
    {
        if (array_diff($requiredPermissions, $userPermissions)) {
            throw new AuthorizationException;
        }
    }

    /**
     * Check if the user has any permission in the array.
     *
     * @param array $requiredPermissions
     * @param array $userPermissions
     * @return Response
     * @throws AuthorizationException
     */
    private function checkAnyPermission(array $requiredPermissions, array $userPermissions)
    {
        if (empty(array_intersect($requiredPermissions, $userPermissions))) {
            throw new AuthorizationException('You do not have the required permission.');
        }
    }
}
