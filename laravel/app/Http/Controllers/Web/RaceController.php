<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Race;
use App\Services\RaceService;

/**
 * Controller handled to display race listings and detailed information.
 */
class RaceController extends Controller
{
    protected $raceService;

    public function __construct(RaceService $raceService)
    {
        $this->raceService = $raceService;
    }

    /**
     * Display a listing of all races with their associated raid start dates.
     */
    public function index()
    {
        $races = $this->raceService->getAll();

        return view('public.races.index', compact('races'));
    }

    /**
     * Display the detailed information for a specific race.
     */
    public function show($race_id)
    {
        // Find the race by ID while eager loading raid and age categories relationships
        $race = $this->raceService->getById($race_id);

        // Redirect to index if the race does not exist
        if (! $race) {
            return redirect()->route('races.index')->with('error', 'Race not found');
        }

        return view('public.races.show', compact('race'));
    }
}
