<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

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
        return view('pages.create.registerTeam');
    }

    /**
     * Handle the registration of a new team and its members.
     */
    public function register(Request $request)
    {
        $raceId = $request->input('race_id');

        // Validation rules for team data and participants array
        $validated = $request->validate([
            'race_id' => ['required', 'integer'],
            'team_name' => ['required', 'string', 'max:50'],
            'participants' => ['required', 'array', 'min:1', 'max:9'],
            'participants.*.pseudo' => ['required', 'string', 'max:50'],
            'participants.*.license' => ['nullable', 'string', 'max:50'],
            'participants.*.pps' => ['nullable', 'string', 'max:128'],
        ], [
            'race_id.required' => "Missing race ID (race_id).",
            'participants.min' => 'At least 1 participant is required.',
            'participants.max' => 'Maximum of 9 participants allowed.',
        ]);

        // Specific business rule: Each participant must provide EITHER a license OR a PPS (Health Safety Course)
        foreach ($validated['participants'] as $i => $p) {
            $hasLicence = !empty($p['license']);
            $hasPps = !empty($p['pps']);

            if (!$hasLicence && !$hasPps) {
                return back()
                    ->withInput()
                    ->withErrors([
                        "participants.$i.license" => "Participant #" . ($i + 1) . " : vous devez fournir un numéro de licence OU un certificat PPS.",
                        "participants.$i.pps" => "Participant #" . ($i + 1) . " : vous devez fournir un numéro de licence OU un certificat PPS.",
                    ]);
            }
        }

        // Ensure the current user is authenticated to act as team captain
        $captainUserId = auth()->id();
        if (!$captainUserId) {
            return back()->withInput()->withErrors([
                'auth' => "Vous devez être connecté pour enregistrer une équipe.",
            ]);
        }

        try {
            // Start a database transaction to ensure data integrity
            DB::beginTransaction();

            // Verify that the target race exists in the database
            $raceExists = DB::table('vik_race')->where('race_id', $raceId)->exists();
            if (!$raceExists) {
                DB::rollBack();
                return back()->withInput()->withErrors([
                    'race_id' => "Race $raceId does not exist.",
                ]);
            }

            // Create the team and retrieve its generated ID
            $teamId = DB::table('vik_team')->insertGetId([
                'race_id' => $raceId,
                'user_id' => $captainUserId,
                'team_name' => $validated['team_name'],
            ], 'team_id');


            // Registration of members/participants
            foreach ($validated['participants'] as $p) {
                $pseudo = $p['pseudo'];

                // Find the member record by their username
                $member = DB::table('vik_member')
                    ->where('user_username', $pseudo)
                    ->first(['user_id']);

                if (!$member) {
                    DB::rollBack();
                    return back()->withInput()->withErrors([
                        'participants' => "Le participant '$pseudo' n'existe pas (pseudo invalide).",
                    ]);
                }

                $userId = $member->user_id;

                // Check if the participant is already registered for this specific race
                $alreadyJoined = DB::table('vik_join_race')
                    ->where('user_id', $userId)
                    ->where('race_id', $raceId)
                    ->exists();

                if ($alreadyJoined) {
                    DB::rollBack();
                    return back()->withInput()->withErrors([
                        'participants' => "Le participant '$pseudo' est déjà inscrit à cette course.",
                    ]);
                }

                // Insert the relationship between the member, the race, and the team
                DB::table('vik_join_race')->insert([
                    'user_id' => $userId,
                    'race_id' => $raceId,
                    'team_id' => $teamId,
                    'jrace_licence_num' => !empty($p['license']) ? $p['license'] : null,
                    'jrace_pps' => !empty($p['pps']) ? $p['pps'] : null,
                ]);
            }

            // All operations succeeded, commit changes to the database
            DB::commit();

            return redirect()
                ->route('race.info', ['race_id' => $raceId])
                ->withSuccess("Equipes et membres enregistrées avec succès.");

        } catch (\Throwable $e) {
            // If any error occurs, revert all database changes
            DB::rollBack();

            return back()->withInput()->withErrors([
                'error' => "Une erreur s'est produite lors de l'enregistrement (aucune donnée n'a été sauvegardée).",
            ]);
        }
    }
}