<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\RaceService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class RegisterRaceController extends Controller
{
    protected $raceService;

    public function __construct(RaceService $raceService)
    {
        $this->raceService = $raceService;
    }

    /**
     * Register to a race
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'race_id' => 'required|integer',
            'team_id' => 'required|integer',
            'jrace_licence_num' => 'nullable|integer',
            'jrace_pps' => 'nullable|string|max:128',
        ]);

        $validated['user_id'] = Auth::id();

        try {
            $this->raceService->registerToRace($validated);
            return redirect()->route('dashboard')->with('success', 'Inscription à la course réussie !');
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de l\'inscription (doublon ou problème technique).');
        }
    }
}
