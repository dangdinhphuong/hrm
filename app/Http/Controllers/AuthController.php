<?php

namespace App\Http\Controllers;

use App\Http\Resources\MeResource;
use App\Models\System\User;
use App\Services\System\AuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    private $authService;

    public function __construct(AuthService $userService)
    {
        $this->authService = $userService;
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required'
        ]);

        $user = User::where('username', $request->username)->first();
        if (!$user || !Hash::check($request->password, $user->password)) {
            return responder()->unauthorized();
        }

        $token = $this->authService->createAccessToken($user);

        return responder()->success([
            'access_token' => $token->plainTextToken,
            'expired_at' => $token->accessToken->expires_at->format('Y-m-d H:i:s'),
            'user' => new MeResource($user)
        ]);
    }

    public function logout()
    {
        auth()->user()->currentAccessToken()->delete();
        return responder()->success("Logout successful");
    }

    public function meInfo()
    {
        $user = auth()->user()->load(['employee.avatarAttachment.attachment']);
        return responder()->success(new MeResource($user));
    }
}
