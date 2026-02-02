<?php

namespace Modules\Auth\Services;

use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Modules\Core\Models\User;

final class AuthService
{

    /**
     * Register a new user and issue an API token.
     *
     * Expected data keys:
     * - name: string
     * - email: string
     * - password: string (plain, will be hashed)
     *
     * @param array<string, mixed> $data Validated registration data.
     *
     * @return array{
     *     user: User,
     *     token: string
     * }
     */
    public function register(array $data): array
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        $token = $user->createToken('api')->plainTextToken;

        return [
            'user' => $user,
            'token' => $token,
        ];
    }

    /**
     * Authenticate user credentials and issue an API token.
     *
     * Expected data keys:
     * - email: string
     * - password: string
     *
     * @param array<string, mixed> $data Validated login credentials.
     *
     * @return array{
     *     user: User,
     *     token: string
     * }
     *
     * @throws ValidationException If credentials are invalid.
     */
    public function login(array $data): array
    {
        $user = User::query()->where('email', $data['email'])->first();

        $token = $user->createToken('api')->plainTextToken;

        return [
            'user' => $user,
            'token' => $token,
        ];
    }

    /**
     * Revoke the currently active access token for the given user.
     *
     * @param User $user Authenticated user.
     *
     * @return void
     */
    public function logoutCurrent(User $user): void
    {
        $user->currentAccessToken()?->delete();
    }
}
