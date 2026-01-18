<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Controller handled to manage races from the perspective of a race manager.
 * Includes participant validation and result importing features.
 */
class RaceManagmentController extends Controller
{
    /**
     * Display a listing of races managed by the currently authenticated user.
     */
    public function index()
    {
        // Retrieve races by joining with the management pivot table and selecting key details
        $races = DB::table('vik_race')
            ->join('vik_race_manager', 'vik_race.race_id', '=', 'vik_race_manager.race_id')
            ->select(
                'vik_race.race_id',
                'vik_race.race_name',
                'vik_race.race_start_date',
                'vik_race.race_duration',
                'vik_race.race_max_team',
                'vik_race.raid_id'
            )
            // Filter only the races where the current user is assigned as manager
            ->where('vik_race_manager.user_id', '=', Auth::user()->user_id)
            ->get();

        return view('pages/race/racesManagment', compact('races'));
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
     * Display the specified race with its grouped participants and teams.
     */
    public function show(string $id)
    {
        // Fetch race and parent raid information
        $race = DB::table('vik_race')
            ->join('vik_raid', 'vik_race.raid_id', '=', 'vik_raid.raid_id')
            ->where('vik_race.race_id', $id)
            ->first();

        if (!$race) { abort(404); }

        // Fetch all members registered for this race and join with their respective teams
        $members = DB::table('vik_join_race')
            ->join('vik_member', 'vik_join_race.user_id', '=', 'vik_member.user_id')
            // Using leftJoin so participants without a team yet are still included
            ->leftJoin('vik_team', 'vik_join_race.team_id', '=', 'vik_team.team_id')
            ->where('vik_join_race.race_id', $id)
            ->select(
                'vik_member.user_id',
                'vik_member.mem_name',
                'vik_member.mem_firstname',
                'vik_join_race.jrace_payement_valid',
                'vik_join_race.jrace_presence_valid',
                'vik_join_race.jrace_licence_num',
                'vik_join_race.jrace_pps',
                'vik_join_race.team_id as j_team_id', // Alias to avoid ID conflicts
                'vik_team.team_name'
            )
            ->get()
            // Group the collection by team ID for a cleaner view display
            ->groupBy('j_team_id');

        return view('pages/race/raceInfoManagment', compact('race', 'members'));
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update participant or team validation status.
     */
    public function update(Request $request, string $id) // $id is the race_id
    {
        // Case 1: Full team validation
        if ($request->has('team_id')) {
            $teamId = $request->input('team_id');

            // Directly update all members belonging to this team for this specific race
            DB::table('vik_join_race')
                ->where('race_id', $id)
                ->where('team_id', $teamId)
                ->update([
                    'jrace_payement_valid' => 1,
                    'jrace_presence_valid' => 1
                ]);

            return back()->with('success', 'Team validated successfully');
        }

        // Case 2: Individual update (via AJAX / Checkbox)
        if ($request->has('user_id')) {
            $userId = $request->input('user_id');
            $field = $request->input('field'); // 'jrace_payement_valid' or 'jrace_presence_valid'
            $value = $request->input('value');

            DB::table('vik_join_race')
                ->where('race_id', $id)
                ->where('user_id', $userId)
                ->update([$field => $value]);

            return response()->json(['success' => true]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    /**
     * Import race results from a CSV file for a specific race.
     * Dynamically handles column ordering and data cleaning.
     */
    public function importCsv(Request $request, string $id)
    {
        // 1. File validation
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt'
        ]);

        $file = $request->file('csv_file');
        $handle = fopen($file->getRealPath(), 'r');

        // 2. Retrieve header and map column names
        $header = fgetcsv($handle, 0, ';');
        if (!$header) {
            fclose($handle);
            return back()->with('error', "The CSV file is empty.");
        }

        // Header cleanup (removes Excel's invisible UTF-8 BOM if present)
        $header[0] = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $header[0]);
        $cols = array_flip(array_map('trim', $header));

        // Check for required column names
        if (!isset($cols['EQUIPE'], $cols['TEMPS'], $cols['PTS'])) {
            fclose($handle);
            return back()->with('error', "Missing columns (EQUIPE, TEMPS, PTS are required).");
        }

        $updatedCount = 0;
        DB::beginTransaction();

        try {
            while (($data = fgetcsv($handle, 0, ';')) !== FALSE) {
                if (count($data) < count($header)) continue;

                // Name cleanup: remove whitespace and Excel non-breaking spaces
                $teamNameCsv = trim(str_replace("\xc2\xa0", ' ', $data[$cols['EQUIPE']]));
                $time = $this->sanitizeTime($data[$cols['TEMPS']]);
                $points = (int) $data[$cols['PTS']];

                if (empty($teamNameCsv)) continue;

                /** * DATA SYNC LOGIC:
                 * Search for the team by its NAME (case-insensitive and trimmed)
                 * We bypass the race_id constraint on existing teams but update it during sync
                 */
                $affected = DB::table('vik_team')
                    ->whereRaw('LOWER(TRIM(team_name)) = ?', [strtolower($teamNameCsv)])
                    ->update([
                        'TEAM_TIME'  => $time,
                        'TEAM_POINT' => $points,
                        'race_id'    => $id // Attach the team to the correct race ID during update
                    ]);
                    
                if ($affected) $updatedCount++;
            }
            
            DB::commit();
            fclose($handle);
            
            return back()->with('success', "Importation réussi: $updatedCount équipes mises à jour.");

        } catch (\Exception $e) {
            DB::rollBack();
            fclose($handle);
            return back()->with('error', "Error: " . $e->getMessage());
        }
    }

    /**
     * Cleans and formats time strings for MySQL (TIME type).
     * Handles durations exceeding 24h and cleans input errors.
     */
    private function sanitizeTime($time)
    {
        $time = trim($time);

        // 1. Handle negative times (common Excel export error) or empty values
        if (!$time || str_contains($time, '-')) {
            return '00:00:00';
        }

        // 2. Validate HH:MM:SS or H:M:S format
        // Regex: any number of hours : 00-59 minutes : 00-59 seconds
        if (preg_match('/^(\d+):([0-5]?[0-9]):([0-5]?[0-9])$/', $time, $matches)) {
            // Reconstruct properly padded string (e.g., 5:4:9 becomes 05:04:09)
            return sprintf('%02d:%02d:%02d', $matches[1], $matches[2], $matches[3]);
        }

        // 3. Fallback for unexpected formats
        return '00:00:00';
    }
}