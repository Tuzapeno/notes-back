<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use App\TokenAbility;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $fields = $request->validated();

        $user = User::create($fields);

        $tokenAccess = $user->createToken('auth_token', [TokenAbility::ACCESS_API]);
        $refreshToken = $user->createToken('refresh_token', [TokenAbility::ISSUE_ACCESS_TOKEN]);

        return response()->json([
            'user' => $user,
            'token' => $tokenAccess->plainTextToken,
        ], status: 200)->cookie(
            'refresh_token',
            $refreshToken->plainTextToken,
            config('sanctum.expiration'),
            '/api/refresh_token',
            null,
            false,
            true,
        );
    }

    public function refreshToken(Request $request)
    {
        $user = $request->user();

        $refreshToken = $user->createToken('refresh_token', [TokenAbility::ACCESS_API]);

        return response()->json([
            'message' => 'Token refreshed successfully',
            'refresh_token' => $refreshToken->plainTextToken,
        ]);
    }

    public function login(LoginRequest $request)
    {
        $fields = $request->validated();

        // Check email
        $user = User::where('email', $fields['email'])->first();

        if (! $user || ! password_verify($fields['password'], $user->password)) {
            return response([
                'message' => 'Invalid credentials',
            ], 422);
        }

        $tokenAccess = $user->createToken('auth_token', [TokenAbility::ACCESS_API]);
        $refreshToken = $user->createToken('refresh_token', [TokenAbility::ISSUE_ACCESS_TOKEN]);

        return response()->json([
            'user' => $user,
            'token' => $tokenAccess->plainTextToken,
        ], status: 200)->cookie(
            'refresh_token',
            $refreshToken->plainTextToken,
            config('sanctum.expiration'),
            'api/refresh_token',
            null,
            false,
            true,
        );
    }

    public function logout(Request $request)
    {
        $request->user()->tokens->each(function ($token) {
            $token->delete();
        });

        return response()->json(['message' => 'Successfully logged out'], status: 200)->cookie(
            'refresh_token',
            '',
            -1,
            'api/refresh_token',
            null,
            false,
            true,
        );

    }
}
