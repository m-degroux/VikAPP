<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreClubRequest;
use App\Http\Requests\UpdateClubRequest;
use App\Services\ClubService;
use Illuminate\Http\JsonResponse;
use OpenApi\Annotations as OA;

class ClubController extends Controller
{
    protected ClubService $clubService;

    public function __construct(ClubService $clubService)
    {
        $this->clubService = $clubService;
    }

    /**
     * @OA\Get(
     *     path="/clubs",
     *     tags={"Clubs"},
     *     summary="List all clubs",
     *     description="Get a list of all sports clubs in the system",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         description="Search by club name",
     *         required=false,
     *         @OA\Schema(type="string", example="Viking")
     *     ),
     *     @OA\Parameter(
     *         name="active",
     *         in="query",
     *         description="Filter by active status",
     *         required=false,
     *         @OA\Schema(type="boolean", example=true)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="List of clubs",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="club_id", type="integer", example=1),
     *                     @OA\Property(property="club_name", type="string", example="Viking Trail Club"),
     *                     @OA\Property(property="club_adr", type="string", example="123 Mountain Road"),
     *                     @OA\Property(property="club_active", type="boolean", example=true),
     *                     @OA\Property(property="members_count", type="integer", example=45)
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated")
     * )
     */
    public function index(): JsonResponse
    {
        $clubs = $this->clubService->getAllClubs();

        return response()->json(['data' => $clubs]);
    }

    /**
     * @OA\Post(
     *     path="/clubs",
     *     tags={"Clubs"},
     *     summary="Create a new club",
     *     description="Register a new sports club",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"club_name","club_adr"},
     *             @OA\Property(property="club_name", type="string", example="New Trail Club"),
     *             @OA\Property(property="club_adr", type="string", example="456 Valley Street"),
     *             @OA\Property(property="club_mail", type="string", format="email", example="contact@club.com"),
     *             @OA\Property(property="club_tel", type="string", example="+33123456789")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Club created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="object")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */
    public function store(StoreClubRequest $request): JsonResponse
    {
        $club = $this->clubService->createClub($request->validated());

        return response()->json(['data' => $club], 201);
    }

    /**
     * @OA\Get(
     *     path="/clubs/{id}",
     *     tags={"Clubs"},
     *     summary="Get a club by ID",
     *     description="Retrieve detailed information about a specific club",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Club ID",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Club details",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="object")
     *         )
     *     ),
     *     @OA\Response(response=404, description="Club not found"),
     *     @OA\Response(response=401, description="Unauthenticated")
     * )
     */
    public function show(int $id): JsonResponse
    {
        $club = $this->clubService->getClubById($id);

        if (! $club) {
            return response()->json(['message' => 'Club non trouvé'], 404);
        }

        return response()->json(['data' => $club]);
    }

    /**
     * @OA\Put(
     *     path="/clubs/{id}",
     *     tags={"Clubs"},
     *     summary="Update a club",
     *     description="Update club information",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Club ID",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="club_name", type="string", example="Updated Club Name"),
     *             @OA\Property(property="club_adr", type="string", example="New Address"),
     *             @OA\Property(property="club_active", type="boolean", example=true)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Club updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="object")
     *         )
     *     ),
     *     @OA\Response(response=404, description="Club not found"),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */
    public function update(UpdateClubRequest $request, int $id)
    {
        $club = $this->clubService->updateClub($id, $request->validated());

        if (! $club) {
            return response()->json(['message' => 'Club non trouvé'], 404);
        }

        return response()->json(['data' => $club]);
    }

    /**
     * @OA\Delete(
     *     path="/clubs/{id}",
     *     tags={"Clubs"},
     *     summary="Delete a club",
     *     description="Permanently delete a club and deactivate its members",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Club ID",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Club deleted successfully"
     *     ),
     *     @OA\Response(response=404, description="Club not found"),
     *     @OA\Response(response=401, description="Unauthenticated")
     * )
     */
    public function destroy(int $id): JsonResponse
    {
        $deleted = $this->clubService->deleteClub($id);

        if (! $deleted) {
            return response()->json(['message' => 'Club non trouvé'], 404);
        }

        return response()->json(null, 204);
    }
}
