<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Race;
use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class TeamCreationController extends Controller
{
    /**
     * Display the team creation form.
     */
    public function create(Request $request)
    {
        $selectedRaceId = $request->query('race_id');
        
        $race = null;
        if ($selectedRaceId) {
            $race = Race::find($selectedRaceId);
        }
        
        return view('dashboard.teams.create', compact('race', 'selectedRaceId'));
    }

    /**
     * Store a newly created team.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'team_name' => 'required|string|max:50',
            'race_id' => 'required|exists:vik_race,race_id',
        ]);

        try {
            $user = Auth::guard('web')->user() ?? Auth::guard('admin')->user();
            
            $team = Team::create([
                'team_name' => $validated['team_name'],
                'race_id' => $validated['race_id'],
                'user_id' => $user->user_id,
            ]);

            Log::info('Team created successfully: ' . $team->team_id);
            
            return redirect()->route('races.show', ['race' => $validated['race_id']])
                ->with('success', 'Équipe créée avec succès !');
                
        } catch (\Exception $e) {
            Log::error('Error creating team: ' . $e->getMessage());
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Erreur lors de la création de l\'équipe : ' . $e->getMessage());
        }
    }
}
