<?php

namespace App\Services;

use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Hash;
use Throwable;

class AuthService
{
    public function login(array $validated): string
    {
        try {
            $user = User::query()
            ->where('email', $validated['email'])
            ->first();

            if (!$user || !Hash::check($validated['password'], $user->password)) {
                throw new Exception("The provided credentials are incorrect.");
            }

            // delet old token
            $user->tokens()->delete();

            return $user->createToken('api-token', ['*'], now()->addMinutes(60))->plainTextToken;
        } catch (Throwable $exception) {
            throw $exception;
        }
    }
}