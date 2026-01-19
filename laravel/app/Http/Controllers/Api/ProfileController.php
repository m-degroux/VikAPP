<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

/**
 * @OA\Tag(
 *     name="Profile",
 *     description="User profile management endpoints"
 * )
 */
class ProfileController extends Controller
{
    /**
     * @OA\Get(
     *     path="/profile",
     *     tags={"Profile"},
     *     summary="Get user profile",
     *     description="Retrieve authenticated user's complete profile information including races and teams",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Profile retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(
     *                 property="user",
     *                 type="object",
     *                 @OA\Property(property="user_id", type="integer", example=42),
     *                 @OA\Property(property="user_username", type="string", example="john_doe"),
     *                 @OA\Property(property="mem_firstname", type="string", example="John"),
     *                 @OA\Property(property="mem_name", type="string", example="Doe"),
     *                 @OA\Property(property="mem_mail", type="string", example="john.doe@example.com"),
     *                 @OA\Property(property="mem_phone", type="string", example="+33612345678"),
     *                 @OA\Property(property="mem_size", type="integer", example=180),
     *                 @OA\Property(property="mem_weight", type="number", example=75.5),
     *                 @OA\Property(property="club", type="object", nullable=true)
     *             )
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated")
     * )
     */
    public function show(Request $request): JsonResponse
    {
        $user = $request->user()->load(['club', 'races', 'teams']);
        
        return response()->json([
            'success' => true,
            'user' => $user,
        ]);
    }
}
