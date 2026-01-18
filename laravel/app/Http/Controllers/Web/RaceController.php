<?php

namespace App\Http\Controllers\Web;

use App\Models\Race;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

/**
 * Controller handled to display race listings and detailed information.
 */
class RaceController extends Controller
{
    /**
     * Display a listing of all races with their associated raid start dates.
     */
    public function index()
    {
        // Retrieve races by joining with the vik_raid table to access raid dates
        $races = DB::table('vik_race')
            ->join('vik_raid', 'vik_race.raid_id', '=', 'vik_raid.raid_id')
            ->select(
                'vik_race.race_id',
                'vik_race.race_name',
                // Using the raid's start date as the race's reference date
                'vik_raid.raid_start_date as race_start_date' 
            )
            ->get();

        return view('race/races', compact('races'));
    }

    /**
     * Display the detailed information for a specific race.
     */
    public function info($race_id)
    {
        // Find the race by ID while eager loading raid and age categories relationships
        $race = Race::with(['raid', 'ageCategories'])->find($race_id);

        // Redirect to index if the race does not exist
        if (!$race) {
            return redirect()->route('race.index')->with('error', 'Race not found');
        }
        
        return view('pages.race/raceInfo', compact('race'));
    }
}