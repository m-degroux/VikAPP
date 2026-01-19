<?php

namespace Database\Seeders;

use App\Models\JoinRace;
use App\Models\JoinTeam;
use App\Models\Member;
use App\Models\Team;
use Illuminate\Database\Seeder;

class ParticipationSeeder extends Seeder
{
    public function run(): void
    {
        $members = Member::all();
        $teams = Team::all();

        if ($teams->isEmpty()) {
            $this->command->warn('No teams found - skipping participations');

            return;
        }

        // Create team race participations + team members
        foreach ($teams as $team) {
            // Add 3-5 members to each team
            $memberCount = rand(3, 5);
            $teamMembers = $members->random(min($memberCount, $members->count()));

            foreach ($teamMembers as $member) {
                // Add to team - columns are user_id and team_id
                if (! JoinTeam::where('user_id', $member->user_id)
                    ->where('team_id', $team->team_id)
                    ->exists()) {
                    JoinTeam::factory()->create([
                        'user_id' => $member->user_id,
                        'team_id' => $team->team_id,
                    ]);
                }

                // Register in race with team
                if (! JoinRace::where('user_id', $member->user_id)
                    ->where('race_id', $team->race_id)
                    ->where('team_id', $team->team_id)
                    ->exists()) {
                    JoinRace::factory()->create([
                        'user_id' => $member->user_id,
                        'race_id' => $team->race_id,
                        'team_id' => $team->team_id,
                    ]);
                }
            }
        }
    }
}
