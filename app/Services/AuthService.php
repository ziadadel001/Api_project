<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthService
{
    /**
     * Register a new user and generate API token
     * 
     * @param array $data Validated registration data (name, email, password)
     * @return array Contains created User instance and API token
     */
    public function register(array $data): array
    {
        // Create user with hashed password
        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        // Generate new API token for the user
        $token = $user->createToken('api_token')->plainTextToken;

        return compact('user', 'token');
    }

    /**
     * Authenticate user and generate API token
     * 
     * @param array $data Validated login data (email, password)
     * @return array Contains authenticated User instance and API token
     * @throws ValidationException If credentials are invalid
     */
    public function login(array $data): array
    {
        // Find user by email
        $user = User::where('email', $data['email'])->first();

        // Verify credentials
        if (!$user || !Hash::check($data['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        // Generate new API token (revokes existing tokens if using sanctum token abilities)
        $token = $user->createToken('api_token')->plainTextToken;

        return compact('user', 'token');
    }

    /**
     * Revoke current access token for authenticated user
     * 
     * @param User $user Authenticated user instance
     */
    public function logout(User $user): void
    {
        // Delete the current access token
        $user->currentAccessToken()->delete();
    }
}
