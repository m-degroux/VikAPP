<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreClubRequest;
use App\Http\Requests\UpdateClubRequest;
use App\Services\ClubService;
use Illuminate\Http\JsonResponse;

class ClubController extends Controller
{
    protected ClubService $clubService;

    public function __construct(ClubService $clubService)
    {
        $this->clubService = $clubService;
    }

    /**
     * Get all clubs
     */
    public function index(): JsonResponse
    {
        $clubs = $this->clubService->getAllClubs();
        return response()->json($clubs);
    }

    /**
     * Create a new club
     */
    public function store(StoreClubRequest $request): JsonResponse
    {
        $club = $this->clubService->createClub($request->validated());

        return response()->json([
            'message' => 'Club créé avec succès',
            'data' => $club
        ], 201);
    }

    /**
     * Get a club by id
     */
    public function show(int $id): JsonResponse
    {
        $club = $this->clubService->getClubById($id);

        if (!$club) {
            return response()->json(['message' => 'Club non trouvé'], 404);
        }

        return response()->json($club);
    }

    /**
     * Update a club
     */
    public function update(UpdateClubRequest $request, int $id)
    {
        $club = $this->clubService->updateClub($id, $request->validated());

        if (!$club) {
            return response()->json(['message' => 'Club non trouvé'], 404);
        }

        return response()->json([
            'message' => 'Club mis à jour avec succès',
            'data' => $club
        ]);
    }
}
