<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Resources\UserResource;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    protected AuthService $auth;

    public function __construct(AuthService $auth)
    {
        $this->auth = $auth;
    }

    public function register(RegisterRequest $request)
    {
        $data = $this->auth->register($request->validated());

        return response()->json([
            'message' => 'User registered successfully.',
            'user'    => new UserResource($data['user']),
            'token'   => $data['token'],
        ], 201);
    }

    public function login(LoginRequest $request): JsonResponse
    {
        $data = $this->auth->login($request->validated());

        return response()->json([
            'message' => 'Login successful.',
            'user'    => new UserResource($data['user']),
            'token'   => $data['token'],
        ]);
    }

    public function logout(Request $request)
    {
        $this->auth->logout($request->user());

        return response()->json([
            'message' => 'Logged out successfully.',
        ]);
    }
}
