<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\SignupRequest;
use App\Http\Requests\UpdateProfileRequest;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    protected AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    /**
     * Login a user
     */
    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        $result = $this->authService->login($request->username, $request->password);

        return response()->json([
            'success' => true,
            'access_token' => $result['token'],
            'token_type' => 'Bearer',
            'user' => [
                'id' => $result['user']->user_id,
                'name' => $result['user']->mem_name,
                'firstname' => $result['user']->mem_firstname
            ]
        ]);
    }

    /**
     * Signup a user
     */
    public function signup(SignupRequest $request): JsonResponse
    {
        $result = $this->authService->signup($request->validated());

        return response()->json([
            'success' => true,
            'access_token' => $result['token'],
            'user' => $result['user']
        ], 201);
    }

    /**
     * Update a user profile
     */
    public function update(UpdateProfileRequest $request): JsonResponse
    {
        $member = $this->authService->updateProfile(
            $request->user(),
            $request->validated()
        );

        return response()->json([
            'success' => true,
            'message' => 'Profil mis à jour avec succès.',
            'user' => $member
        ]);
    }

    /**
     * Logout a user
     */
    public function logout(Request $request): JsonResponse
    {
        $request->user()->tokens()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logged out successfully'
        ]);
    }
}
