<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRaidRequest;
use App\Http\Resources\RaidResource;
use App\Models\Raid;
use App\Services\RaidService;


class RaidController extends Controller
{

    protected $raidService;

    public function __construct(RaidService $raidService)
    {
        $this->raidService = $raidService;
    }

    /**
     * Get all raids
     */
    public function index()
    {
        $raids = $this->raidService->getAllRaids();

        return response()->json([
            'success' => true,
            'data' => RaidResource::collection($raids)
        ]);
    }

    /**
     * Get a raid by id
     */
    public function show(string $id)
    {
        $raid = $this->raidService->getRaidById($id);

        if (!$raid) {
            return response()->json([
                'success' => false,
                'message' => 'Raid non trouvé'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => new RaidResource($raid)
        ]);
    }

    /**
     * Create a new raid
     */
    public function store(StoreRaidRequest $request)
    {
        $validatedData = $request->validated();

        $raid = $this->raidService->createRaid($validatedData, auth()->id());

        return response()->json($raid, 201);
    }
}
