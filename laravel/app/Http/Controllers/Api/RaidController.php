<?php

namespace App\Http\Controllers\Api;

use App\Actions\Raid\CreateRaid;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRaidRequest;
use App\Http\Requests\UpdateRaidRequest;
use App\Http\Resources\RaidResource;
use App\Models\Raid;
use App\Services\RaidService;
use OpenApi\Annotations as OA;

class RaidController extends Controller
{
    protected $raidService;

    protected CreateRaid $createRaidAction;

    public function __construct(RaidService $raidService, CreateRaid $createRaidAction)
    {
        $this->raidService = $raidService;
        $this->createRaidAction = $createRaidAction;
    }

    /**
     * @OA\Get(
     *     path="/raids",
     *     tags={"Raids"},
     *     summary="List all raids",
     *     description="Get paginated list of all raids with filtering options",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="Page number for pagination",
     *         required=false,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         description="Items per page",
     *         required=false,
     *         @OA\Schema(type="integer", example=15, maximum=100)
     *     ),
     *     @OA\Parameter(
     *         name="upcoming",
     *         in="query",
     *         description="Filter upcoming raids only",
     *         required=false,
     *         @OA\Schema(type="boolean", example=true)
     *     ),
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         description="Search by name or location",
     *         required=false,
     *         @OA\Schema(type="string", example="viking")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="raid_id", type="integer", example=1),
     *                     @OA\Property(property="raid_name", type="string", example="Viking Trail 2026"),
     *                     @OA\Property(property="raid_start_date", type="string", format="date", example="2026-06-15"),
     *                     @OA\Property(property="raid_location", type="string", example="Chamonix"),
     *                     @OA\Property(property="races_count", type="integer", example=5),
     *                     @OA\Property(property="min_age", type="integer", example=12),
     *                     @OA\Property(property="max_age", type="integer", example=99)
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated"
     *     )
     * )
     */
    public function index()
    {
        $raids = $this->raidService->getAllRaids();

        return response()->json([
            'data' => RaidResource::collection($raids),
        ]);
    }

    /**
     * Get a raid by id
     */
    public function show(string $id)
    {
        $raid = $this->raidService->getRaidById($id);

        if (! $raid) {
            return response()->json([
                'message' => 'Raid non trouvé',
            ], 404);
        }

        return response()->json([
            'data' => new RaidResource($raid),
        ]);
    }

    /**
     * Create a new raid
     */
    public function store(StoreRaidRequest $request)
    {
        $validatedData = $request->validated();

        $raid = $this->createRaidAction->execute($validatedData, auth()->id());

        return response()->json(['data' => $raid], 201);
    }

    /**
     * Update a raid
     */
    public function update(UpdateRaidRequest $request, string $id)
    {
        $raid = Raid::find($id);

        if (! $raid) {
            return response()->json(['message' => 'Raid non trouvé'], 404);
        }

        $raid->update($request->validated());

        return response()->json(['data' => new RaidResource($raid)]);
    }

    /**
     * Delete a raid
     */
    public function destroy(string $id)
    {
        $raid = Raid::find($id);

        if (! $raid) {
            return response()->json(['message' => 'Raid non trouvé'], 404);
        }

        $raid->delete();

        return response()->json(null, 204);
    }
}

    /**
     * @OA\Get(
     *     path="/raids/{id}",
     *     tags={"Raids"},
     *     summary="Get raid by ID",
     *     description="Get detailed information about a specific raid",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Raid ID",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="raid_id", type="integer", example=1),
     *                 @OA\Property(property="raid_name", type="string", example="Viking Trail 2026"),
     *                 @OA\Property(property="raid_description", type="string"),
     *                 @OA\Property(property="raid_start_date", type="string", format="date"),
     *                 @OA\Property(property="raid_location", type="string"),
     *                 @OA\Property(property="raid_gps_lat", type="number", format="float"),
     *                 @OA\Property(property="raid_gps_long", type="number", format="float"),
     *                 @OA\Property(property="type", type="object"),
     *                 @OA\Property(property="difficulty", type="object"),
     *                 @OA\Property(property="races", type="array", @OA\Items(type="object"))
     *             )
     *         )
     *     ),
     *     @OA\Response(response=404, description="Raid not found")
     * )
     */

    /**
     * @OA\Post(
     *     path="/raids",
     *     tags={"Raids"},
     *     summary="Create new raid",
     *     description="Create a new raid event",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"raid_name","raid_start_date","raid_location","type_id","diff_id","club_id"},
     *             @OA\Property(property="raid_name", type="string", example="New Viking Trail"),
     *             @OA\Property(property="raid_description", type="string"),
     *             @OA\Property(property="raid_start_date", type="string", format="date", example="2026-07-01"),
     *             @OA\Property(property="raid_location", type="string", example="Annecy"),
     *             @OA\Property(property="raid_address", type="string"),
     *             @OA\Property(property="raid_gps_lat", type="number", format="float", example=45.8992),
     *             @OA\Property(property="raid_gps_long", type="number", format="float", example=6.1294),
     *             @OA\Property(property="type_id", type="integer", example=1),
     *             @OA\Property(property="diff_id", type="integer", example=2),
     *             @OA\Property(property="club_id", type="integer", example=5)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Raid created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="raid_id", type="integer"),
     *             @OA\Property(property="raid_name", type="string"),
     *             @OA\Property(property="message", type="string", example="Raid created successfully")
     *         )
     *     ),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */

    /**
     * @OA\Put(
     *     path="/raids/{id}",
     *     tags={"Raids"},
     *     summary="Update raid",
     *     description="Update existing raid information",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Raid ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="raid_name", type="string"),
     *             @OA\Property(property="raid_description", type="string"),
     *             @OA\Property(property="raid_start_date", type="string", format="date"),
     *             @OA\Property(property="raid_location", type="string")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Raid updated successfully"),
     *     @OA\Response(response=404, description="Raid not found")
     * )
     */

    /**
     * @OA\Delete(
     *     path="/raids/{id}",
     *     tags={"Raids"},
     *     summary="Delete raid",
     *     description="Delete a raid and all associated races",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Raid ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Raid deleted successfully"),
     *     @OA\Response(response=404, description="Raid not found")
     * )
     */
