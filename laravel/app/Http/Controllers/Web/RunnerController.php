<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

/**
 * Controller responsible for managing runner statistics, race history, 
 * and upcoming registrations.
 */
class RunnerController extends Controller
{
    /**
     * Display a listing of the runner's dashboard including stats, 
     * upcoming races, and historical performance.
     */
    public function index()
    {
        $currentRunnerId = Auth::user()->user_id;
        $now = Carbon::now();

        // 1. Global Statistics
        // Count total number of race participations
        $nbCourses = DB::table('vik_join_race')->where('user_id', $currentRunnerId)->count();
        
        // Sum total points accumulated across all teams the user managed
        $totalPoints = DB::table('vik_team')->where('user_id', $currentRunnerId)->sum('team_point');

        // 2. UPCOMING RACES
        $upcomingRaces = DB::table('vik_race as r')
            ->join('vik_raid as ra', 'ra.raid_id', '=', 'r.raid_id')
            ->join('vik_team as t', 't.race_id', '=', 'r.race_id')
            ->where('r.race_start_date', '>', $now)
            ->where(function ($q) use ($currentRunnerId) {
                // User is either the team manager or a registered runner in the team
                $q->where('t.user_id', $currentRunnerId)
                    ->orWhereExists(function ($sub) use ($currentRunnerId) {
                        $sub->select(DB::raw(1))
                            ->from('vik_join_race as jr')
                            ->whereColumn('jr.team_id', 't.team_id')
                            ->whereColumn('jr.race_id', 'r.race_id')
                            ->where('jr.user_id', $currentRunnerId);
                    });
            })
            ->selectRaw("
                r.race_name,
                r.race_start_date,
                r.race_length,
                ra.raid_name,
                t.team_name,
                t.team_id,
                CASE
                    WHEN t.user_id = ?
                    AND EXISTS (
                        SELECT 1 FROM vik_join_race jr
                        WHERE jr.user_id = ?
                        AND jr.team_id = t.team_id
                        AND jr.race_id = r.race_id
                    )
                        THEN 'manager/coureur'
                    WHEN t.user_id = ?
                        THEN 'manager'
                    WHEN EXISTS (
                        SELECT 1 FROM vik_join_race jr
                        WHERE jr.user_id = ?
                        AND jr.team_id = t.team_id
                        AND jr.race_id = r.race_id
                    )
                        THEN 'coureur'
                    ELSE 'aucun'
                END AS statut
            ", [$currentRunnerId, $currentRunnerId, $currentRunnerId, $currentRunnerId])
            ->orderBy('r.race_start_date', 'asc')
            ->get();

        // 3. RACE HISTORY
        $history = DB::table('vik_race as r')
            ->join('vik_raid as ra', 'ra.raid_id', '=', 'r.raid_id')
            ->join('vik_team as t', 't.race_id', '=', 'r.race_id')
            ->where('r.race_start_date', '<', $now)
            ->where(function ($q) use ($currentRunnerId) {
                $q->where('t.user_id', $currentRunnerId) // acts as manager
                    ->orWhereExists(function ($sub) use ($currentRunnerId) {
                        $sub->select(DB::raw(1))
                            ->from('vik_join_race as jr')
                            ->whereColumn('jr.team_id', 't.team_id')
                            ->whereColumn('jr.race_id', 'r.race_id')
                            ->where('jr.user_id', $currentRunnerId);
                    });
            })
            ->selectRaw("
                r.race_id,
                r.race_name,
                ra.raid_name,
                t.team_id,
                t.user_id,
                t.team_name,
                t.team_picture,
                t.team_time,
                t.team_point,
                CASE
                    WHEN t.user_id = ?
                    AND EXISTS (
                        SELECT 1 FROM vik_join_race jr
                        WHERE jr.user_id = ?
                        AND jr.team_id = t.team_id
                        AND jr.race_id = r.race_id
                    )
                        THEN 'manager/coureur'
                    WHEN t.user_id = ?
                        THEN 'manager'
                    WHEN EXISTS (
                        SELECT 1 FROM vik_join_race jr
                        WHERE jr.user_id = ?
                        AND jr.team_id = t.team_id
                        AND jr.race_id = r.race_id
                    )
                        THEN 'coureur'
                    ELSE 'aucun'
                END AS statut
            ", [$currentRunnerId, $currentRunnerId, $currentRunnerId, $currentRunnerId])
            ->orderBy('r.race_start_date', 'asc')
            ->get();

        $nbPodiums = 0;

        // Processing rank and podiums for historical data
        foreach ($history as $item) {
            // Count total teams in the same race for context
            $item->total_participants = DB::table('vik_team')
                ->where('race_id', $item->race_id)
                ->count();

            if ($item->team_time !== null) {
                // Calculate rank by counting teams with a better (smaller) time
                $betterTeams = DB::table('vik_team')
                    ->where('race_id', $item->race_id)
                    ->whereNotNull('team_time')
                    ->where('team_time', '<', $item->team_time)
                    ->count();

                $item->rank = $betterTeams + 1;
            } else {
                $item->rank = '-';
            }

            // Increment podium count if the runner finished in top 3
            if ($item->rank !== '-' && $item->rank <= 3) {
                $nbPodiums++;
            }
        }

        return view("pages.statsRunner", [
            'nbCourses' => $nbCourses,
            'totalPoints' => $totalPoints,
            'nbPodiums' => $nbPodiums,
            'history' => $history,
            'upcomingRaces' => $upcomingRaces
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified race results in JSON format.
     */
    public function show(string $id)
    {
        try {
            // Retrieve race teams sorted by time, excluding those who didn't finish
            $results = DB::table('vik_team')
                ->where('race_id', $id)
                ->whereNotNull('team_time') // Essential for ranking logic
                ->orderBy('team_time', 'asc')
                ->get();

            return response()->json($results);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}