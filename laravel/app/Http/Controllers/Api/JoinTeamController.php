<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\JoinTeamRequest;
use App\Services\JoinTeamService;
use Illuminate\Http\JsonResponse;

class JoinTeamController extends Controller
{
    protected JoinTeamService $service;

    public function __construct(JoinTeamService $service)
    {
        $this->service = $service;
    }

    /**
     * Join a team
     */
    public function store(JoinTeamRequest $request): JsonResponse
    {
        $result = $this->service->join($request->validated());

        return response()->json([
            'message' => 'Association créée avec succès',
            'data' => $result
        ], 201);
    }

    /**
     * Leave a team
     */
    public function destroy(int $teamId, int $userId): JsonResponse
    {
        $deleted = $this->service->leave($teamId, $userId);

        if (!$deleted) {
            return response()->json(['message' => 'Association non trouvée'], 404);
        }

        return response()->json(['message' => 'Le membre a été retiré de l\'équipe']);
    }

    /**
     * Get all members of a team
     */
    public function showByTeam(int $teamId): JsonResponse
    {
        $members = $this->service->getMembersByTeam($teamId);
        return response()->json($members);
    }
}
