<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\RaceRegistrationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RegisterRaceController extends Controller
{
    protected RaceRegistrationService $raceRegistrationService;

    public function __construct(RaceRegistrationService $raceRegistrationService)
    {
        $this->raceRegistrationService = $raceRegistrationService;
    }

    /**
     * Register to a race
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'race_id' => 'required|integer',
            'team_id' => 'required|integer',
            'jrace_licence_num' => 'nullable|integer',
            'jrace_pps' => 'nullable|string|max:128',
        ]);

        $validated['user_id'] = Auth::id();

        try {
            $this->raceRegistrationService->registerToRace($validated);

            return response()->json(['message' => 'Inscription à la course réussie !'], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erreur lors de l\'inscription (doublon ou problème technique).'], 500);
        }
    }
}
