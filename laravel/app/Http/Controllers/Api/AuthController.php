<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\SignupRequest;
use App\Http\Requests\UpdateProfileRequest;
use App\Models\Club;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

/**
 * @OA\Info(
 *     title="VIKAPP API",
 *     version="1.0.0",
 *     description="Viking Raids Management System API - Complete documentation for managing trail running events, races, teams and participants",
 *     @OA\Contact(
 *         email="contact@vikapp.com"
 *     )
 * )
 * 
 * @OA\Server(
 *     url="http://localhost:8000/api",
 *     description="Local Development Server"
 * )
 * 
 * @OA\Server(
 *     url="https://api.vikapp.com",
 *     description="Production Server"
 * )
 * 
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT",
 *     description="Enter your Bearer token in the format: Bearer {token}"
 * )
 * 
 * @OA\Tag(
 *     name="Authentication",
 *     description="User authentication and account management endpoints"
 * )
 * 
 * @OA\Tag(
 *     name="Raids",
 *     description="Raid event management operations"
 * )
 * 
 * @OA\Tag(
 *     name="Races",
 *     description="Race management within raids"
 * )
 * 
 * @OA\Tag(
 *     name="Teams",
 *     description="Team registration and management"
 * )
 * 
 * @OA\Tag(
 *     name="Clubs",
 *     description="Sports club management"
 * )
 */
class AuthController extends Controller
{
    protected AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    /**
     * @OA\Post(
     *     path="/login",
     *     tags={"Authentication"},
     *     summary="Login user",
     *     description="Authenticate a user and return access token",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"username","password"},
     *             @OA\Property(property="username", type="string", example="testuser"),
     *             @OA\Property(property="password", type="string", format="password", example="password")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful login",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="access_token", type="string", example="1|abc123def456..."),
     *             @OA\Property(property="token_type", type="string", example="Bearer"),
     *             @OA\Property(
     *                 property="user",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=42),
     *                 @OA\Property(property="name", type="string", example="Doe"),
     *                 @OA\Property(property="firstname", type="string", example="John")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Invalid credentials",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Invalid credentials")
     *         )
     *     )
     * )
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
                'firstname' => $result['user']->mem_firstname,
            ],
        ]);
    }

    /**
     * @OA\Post(
     *     path="/register",
     *     tags={"Authentication"},
     *     summary="Register new user",
     *     description="Create a new member account and return access token",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"user_username","user_password","user_password_confirmation","mem_firstname","mem_name","mem_sex","mem_size","mem_weight","mem_birth_year","mem_mail"},
     *             @OA\Property(property="user_username", type="string", example="john_doe"),
     *             @OA\Property(property="user_password", type="string", format="password", example="SecurePass123!"),
     *             @OA\Property(property="user_password_confirmation", type="string", format="password", example="SecurePass123!"),
     *             @OA\Property(property="mem_firstname", type="string", example="John"),
     *             @OA\Property(property="mem_name", type="string", example="Doe"),
     *             @OA\Property(property="mem_sex", type="string", enum={"M", "F"}, example="M"),
     *             @OA\Property(property="mem_size", type="integer", example=180),
     *             @OA\Property(property="mem_weight", type="number", format="float", example=75.5),
     *             @OA\Property(property="mem_birth_year", type="integer", example=1990),
     *             @OA\Property(property="mem_mail", type="string", format="email", example="john.doe@example.com"),
     *             @OA\Property(property="mem_phone", type="string", example="+33612345678")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="User created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="access_token", type="string", example="2|xyz789..."),
     *             @OA\Property(
     *                 property="user",
     *                 type="object",
     *                 @OA\Property(property="user_id", type="integer", example=43),
     *                 @OA\Property(property="user_username", type="string", example="john_doe"),
     *                 @OA\Property(property="mem_firstname", type="string", example="John"),
     *                 @OA\Property(property="mem_name", type="string", example="Doe")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="The given data was invalid."),
     *             @OA\Property(
     *                 property="errors",
     *                 type="object",
     *                 @OA\Property(
     *                     property="user_username",
     *                     type="array",
     *                     @OA\Items(type="string", example="The username has already been taken.")
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function signup(SignupRequest $request): JsonResponse
    {
        $result = $this->authService->signup($request->validated());

        return response()->json([
            'success' => true,
            'access_token' => $result['token'],
            'user' => $result['user'],
        ], 201);
    }

    /**
     * @OA\Put(
     *     path="/user",
     *     tags={"Authentication"},
     *     summary="Update user profile",
     *     description="Update authenticated user's profile information",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="mem_firstname", type="string", example="John"),
     *             @OA\Property(property="mem_name", type="string", example="Doe"),
     *             @OA\Property(property="mem_mail", type="string", format="email", example="john.doe@example.com"),
     *             @OA\Property(property="mem_phone", type="string", example="+33612345678"),
     *             @OA\Property(property="mem_size", type="integer", example=180),
     *             @OA\Property(property="mem_weight", type="number", format="float", example=75.5)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Profile updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Profil mis à jour avec succès."),
     *             @OA\Property(property="user", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated"
     *     )
     * )
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
            'user' => $member,
        ]);
    }

    /**
     * @OA\Post(
     *     path="/logout",
     *     tags={"Authentication"},
     *     summary="Logout user",
     *     description="Revoke all user's authentication tokens",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Logged out successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Logged out successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated"
     *     )
     * )
     */
    public function logout(Request $request): JsonResponse
    {
        $request->user()->tokens()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logged out successfully',
        ]);
    }

    /**
     * @OA\Delete(
     *     path="/user",
     *     tags={"Authentication"},
     *     summary="Delete user account",
     *     description="Permanently delete the authenticated user's account and all associated data",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Account deleted successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Votre compte a été supprimé définitivement.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Une erreur est survenue lors de la suppression du compte.")
     *         )
     *     )
     * )
     */
    public function destroy(Request $request): JsonResponse
    {
        try {
            $user = $request->user();

            // Find any club managed by this user and delete it
            $managedClub = Club::where('user_id', $user->user_id)->first();
            if ($managedClub) {
                $managedClub->delete();
            }

            // Delete user's tokens
            $user->tokens()->delete();

            // Delete user's account
            $user->delete();

            return response()->json([
                'success' => true,
                'message' => 'Votre compte a été supprimé définitivement.',
            ]);
        } catch (\Exception $e) {
            \Log::error('Error deleting user account: '.$e->getMessage(), ['user_id' => $request->user()->user_id ?? null]);

            return response()->json([
                'success' => false,
                'message' => 'Une erreur est survenue lors de la suppression du compte.',
            ], 500);
        }
    }
}
