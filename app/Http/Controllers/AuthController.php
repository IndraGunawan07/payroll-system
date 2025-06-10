<?php

namespace App\Http\Controllers;

use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Throwable;

class AuthController extends Controller
{
    public function __construct(
        public AuthService $authService,
    ) {
    }

    public function login(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'email' => ['required', 'string'],
                'password' => ['required', 'string'],
            ]);

            $token = $this->authService->login($validated);

            return response()->json([
                'message' => 'Login success',
                'token' => $token,
            ]);
        } catch (Throwable $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
            ], 422);
        }
    }

    public function logout(Request $request): JsonResponse
    {
        try {
            $request->user()->currentAccessToken()->delete();

            return response()->json([
                'message' => 'Logged out successfully!',
            ]);
        } catch (Throwable $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
            ], 422);
        }
    }
}
