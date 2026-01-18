<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRaceRequest;
use App\Http\Requests\UpdateRaceRequest;
use App\Services\RaceService;
use Illuminate\Http\JsonResponse;

class RaceController extends Controller
{
    protected RaceService $raceService;

    public function __construct(RaceService $raceService)
    {
        $this->raceService = $raceService;
    }

    /**
     * Get all races
     */
    public function index(): JsonResponse
    {
        return response()->json($this->raceService->getAll());
    }

    /**
     * Create a new race
     */
    public function store(StoreRaceRequest $request): JsonResponse
    {
        $race = $this->raceService->create($request->validated());
        return response()->json($race, 201);
    }

    /**
     * Get a race by id
     */
    public function show($id): JsonResponse
    {
        $race = $this->raceService->getById($id);
        return $race ? response()->json($race) : response()->json(['message' => 'Race non trouvée'], 404);
    }

    /**
     * Update a race
     */
    public function update(UpdateRaceRequest $request, $id): JsonResponse
    {
        $race = $this->raceService->update($id, $request->validated());
        return $race ? response()->json($race) : response()->json(['message' => 'Race non trouvée'], 404);
    }


}
