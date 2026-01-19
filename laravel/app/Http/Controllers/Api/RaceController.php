<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRaceRequest;
use App\Http\Requests\UpdateRaceRequest;
use App\Services\RaceService;
use Illuminate\Http\JsonResponse;
use OpenApi\Annotations as OA;

class RaceController extends Controller
{
    protected RaceService $raceService;

    public function __construct(RaceService $raceService)
    {
        $this->raceService = $raceService;
    }

    /**
     * @OA\Get(
     *     path="/races",
     *     tags={"Races"},
     *     summary="List all races",
     *     description="Get a list of all races across all raids",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="raid_id",
     *         in="query",
     *         description="Filter by raid ID",
     *         required=false,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="List of races",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="race_id", type="string", example="uuid-123"),
     *                     @OA\Property(property="race_name", type="string", example="Trail 25km"),
     *                     @OA\Property(property="race_length", type="number", example=25.5),
     *                     @OA\Property(property="race_start_date", type="string", format="datetime"),
     *                     @OA\Property(property="race_max_part", type="integer", example=200)
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated")
     * )
     */
    public function index(): JsonResponse
    {
        return response()->json(['data' => $this->raceService->getAll()]);
    }

    /**
     * @OA\Post(
     *     path="/races",
     *     tags={"Races"},
     *     summary="Create a new race",
     *     description="Create a new race within a raid",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"raid_id","race_name","race_length","race_start_date","race_end_date"},
     *             @OA\Property(property="raid_id", type="integer", example=1),
     *             @OA\Property(property="race_name", type="string", example="Trail 25km"),
     *             @OA\Property(property="type_id", type="integer", example=1),
     *             @OA\Property(property="race_length", type="number", example=25.5),
     *             @OA\Property(property="race_start_date", type="string", format="datetime"),
     *             @OA\Property(property="race_end_date", type="string", format="datetime"),
     *             @OA\Property(property="race_min_part", type="integer", example=10),
     *             @OA\Property(property="race_max_part", type="integer", example=200),
     *             @OA\Property(property="race_min_team", type="integer", example=1),
     *             @OA\Property(property="race_max_team", type="integer", example=50),
     *             @OA\Property(property="race_max_part_per_team", type="integer", example=6)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Race created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="object")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */
    public function store(StoreRaceRequest $request): JsonResponse
    {
        $race = $this->raceService->create($request->validated());

        return response()->json([
            'success' => true,
            'data' => $race,
        ], 201);
    }

    /**
     * @OA\Get(
     *     path="/races/{id}",
     *     tags={"Races"},
     *     summary="Get a race by ID",
     *     description="Retrieve detailed information about a specific race",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Race ID",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Race details",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="object")
     *         )
     *     ),
     *     @OA\Response(response=404, description="Race not found"),
     *     @OA\Response(response=401, description="Unauthenticated")
     * )
     */
    public function show($id): JsonResponse
    {
        $race = $this->raceService->getById($id);

        return $race ? response()->json(['data' => $race]) : response()->json(['message' => 'Race non trouvée'], 404);
    }

    /**
     * @OA\Put(
     *     path="/races/{id}",
     *     tags={"Races"},
     *     summary="Update a race",
     *     description="Update race information",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Race ID",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="race_name", type="string", example="Trail 30km"),
     *             @OA\Property(property="race_length", type="number", example=30.0),
     *             @OA\Property(property="race_max_part", type="integer", example=250)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Race updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="object")
     *         )
     *     ),
     *     @OA\Response(response=404, description="Race not found"),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */
    public function update(UpdateRaceRequest $request, $id): JsonResponse
    {
        $race = $this->raceService->update($id, $request->validated());

        return $race ? response()->json(['data' => $race]) : response()->json(['message' => 'Race non trouvée'], 404);
    }

    /**
     * @OA\Delete(
     *     path="/races/{id}",
     *     tags={"Races"},
     *     summary="Delete a race",
     *     description="Permanently delete a race and all associated registrations",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Race ID",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Race deleted successfully"
     *     ),
     *     @OA\Response(response=404, description="Race not found"),
     *     @OA\Response(response=401, description="Unauthenticated")
     * )
     */
    public function destroy($id): JsonResponse
    {
        $deleted = $this->raceService->delete($id);

        return $deleted ? response()->json(null, 204) : response()->json(['message' => 'Race non trouvée'], 404);
    }
}
