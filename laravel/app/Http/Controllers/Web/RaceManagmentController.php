<?php

namespace App\Http\Controllers\Web;

use App\Actions\Race\ImportRaceResults;
use App\Http\Controllers\Controller;
use App\Services\RaceManagerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Controller handled to manage races from the perspective of a race manager.
 * Includes participant validation and result importing features.
 */
class RaceManagmentController extends Controller
{
    protected $raceManagerService;

    public function __construct(RaceManagerService $raceManagerService)
    {
        $this->raceManagerService = $raceManagerService;
    }

    /**
     * Display a listing of races managed by the currently authenticated user.
     */
    public function index()
    {
        $user = Auth::guard('web')->user() ?? Auth::guard('admin')->user();
        
        if (!$user) {
            abort(403, 'Unauthorized');
        }
        
        $races = $this->raceManagerService->getManagedRaces($user->user_id);

        return view('pages/admin/race/management', compact('races'));
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
        $details = $this->raceManagerService->getRaceDetails($id);

        if (! $details['race']) {
            abort(404);
        }

        return view('pages/admin/race/info', $details);
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
            $this->raceManagerService->validateTeam($id, $teamId);

            return back()->with('success', 'Team validated successfully');
        }

        // Case 2: Individual update (via AJAX / Checkbox)
        if ($request->has('user_id')) {
            $userId = $request->input('user_id');
            $field = $request->input('field'); // 'jrace_payement_valid' or 'jrace_presence_valid'
            $value = $request->input('value');

            $this->raceManagerService->updateIndividualValidation($id, $userId, $field, $value);

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
    public function importCsv(Request $request, string $id, ImportRaceResults $importer)
    {
        try {
            $message = $importer->execute($request, $id);

            return back()->with('success', $message);
        } catch (\Exception $e) {
            return back()->with('error', 'Error: '.$e->getMessage());
        }
    }
}
