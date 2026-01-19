<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class RaceResultController extends Controller
{
    /**
     * Retrieve race results (team rankings) for a specific race.
     */
    public function index(string $id)
    {
        try {
            // Retrieve teams for the race, sorted by time, excluding those who haven't finished (null)
            // This is essential for establishing the final ranking
            $results = DB::table('vik_team')
                ->where('race_id', $id)
                ->whereNotNull('team_time') // Important for the leaderboard
                ->orderBy('team_time', 'asc')
                ->get();

            return response()->json($results);
        } catch (\Exception $e) {
            // Return error message in case of failure
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
