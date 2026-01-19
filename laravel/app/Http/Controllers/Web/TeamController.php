<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\JoinRace;
use App\Models\JoinTeam;
use App\Models\Member;
use App\Models\Race;
use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * Controller responsible for team creation and participant registration for a specific race.
 */
class TeamController extends Controller
{
    /**
     * Display the team registration form.
     */
    public function form()
    {
        return view('dashboard.teams.create');
    }

    /**
     * Handle the registration of a new team and its members.
     */
    public function register(Request $request)
    {
        $raceId = $request->input('race_id');

        // Validation rules for team data and participants array
        $validated = $request->validate([
            'race_id' => ['required', 'string'],
            'team_name' => ['required', 'string', 'max:50'],
            'participants' => ['required', 'array', 'min:1', 'max:9'],
            'participants.*.pseudo' => ['required', 'string', 'max:50'],
            'participants.*.license' => ['nullable', 'string', 'max:50'],
            'participants.*.pps' => ['nullable', 'string', 'max:128'],
        ], [
            'race_id.required' => 'Missing race ID (race_id).',
            'participants.min' => 'At least 1 participant is required.',
            'participants.max' => 'Maximum of 9 participants allowed.',
        ]);

        // Specific business rule: Each participant must provide EITHER a license OR a PPS
        foreach ($validated['participants'] as $i => $p) {
            $hasLicence = ! empty($p['license']);
            $hasPps = ! empty($p['pps']);

            if (! $hasLicence && ! $hasPps) {
                return back()
                    ->withInput()
                    ->withErrors([
                        "participants.$i.license" => 'Participant #'.($i + 1).' : vous devez fournir un numéro de licence OU un certificat PPS.',
                        "participants.$i.pps" => 'Participant #'.($i + 1).' : vous devez fournir un numéro de licence OU un certificat PPS.',
                    ]);
            }
        }

        // Ensure the current user is authenticated to act as team captain
        $captainUserId = auth()->id();
        if (! $captainUserId) {
            return back()->withInput()->withErrors([
                'auth' => 'Vous devez être connecté pour enregistrer une équipe.',
            ]);
        }

        try {
            DB::beginTransaction();

            // Verify that the target race exists
            $race = Race::find($raceId);
            if (! $race) {
                DB::rollBack();

                return back()->withInput()->withErrors([
                    'race_id' => "Race $raceId does not exist.",
                ]);
            }

            // Create the team with UUID
            $team = Team::create([
                'team_id' => Str::uuid()->toString(),
                'race_id' => $raceId,
                'user_id' => $captainUserId,
                'team_name' => $validated['team_name'],
            ]);

            // Registration of members/participants
            foreach ($validated['participants'] as $p) {
                $pseudo = $p['pseudo'];

                // Find the member record by their username
                $member = Member::where('user_username', $pseudo)->first();

                if (! $member) {
                    DB::rollBack();

                    return back()->withInput()->withErrors([
                        'participants' => "Le participant '$pseudo' n'existe pas (pseudo invalide).",
                    ]);
                }

                // Check if member already joined this race
                $alreadyJoined = JoinRace::where('user_id', $member->user_id)
                    ->where('race_id', $raceId)
                    ->where('team_id', $team->team_id)
                    ->exists();

                if ($alreadyJoined) {
                    DB::rollBack();

                    return back()->withInput()->withErrors([
                        'participants' => "Le participant '$pseudo' est déjà inscrit à cette course.",
                    ]);
                }

                // Join team
                JoinTeam::create([
                    'team_id' => $team->team_id,
                    'user_id' => $member->user_id,
                ]);

                // Register in race with team
                JoinRace::create([
                    'user_id' => $member->user_id,
                    'race_id' => $raceId,
                    'team_id' => $team->team_id,
                    'jrace_licence_num' => $p['license'] ?? null,
                    'jrace_pps' => $p['pps'] ?? null,
                    'jrace_presence_valid' => false,
                    'jrace_payement_valid' => false,
                ]);
            }

            DB::commit();

            return redirect()->route('dashboard')
                ->with('success', "Équipe '{$validated['team_name']}' inscrite avec succès !");
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->withInput()->withErrors([
                'error' => 'Une erreur est survenue lors de l\'inscription de l\'équipe. '.$e->getMessage(),
            ]);
        }
    }
}
