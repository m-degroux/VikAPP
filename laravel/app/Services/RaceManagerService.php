<?php

namespace App\Services;

use App\Models\JoinRace;
use App\Models\Race;

class RaceManagerService
{
    public function getManagedRaces(int $userId)
    {
        return Race::select(
            'vik_race.race_id',
            'vik_race.race_name',
            'vik_race.race_start_date',
            'vik_race.race_duration',
            'vik_race.race_max_team',
            'vik_race.raid_id'
        )
            ->join('vik_race_manager', 'vik_race.race_id', '=', 'vik_race_manager.race_id')
            ->where('vik_race_manager.user_id', '=', $userId)
            ->get();
    }

    public function getRaceDetails(string $raceId)
    {
        $race = Race::join('vik_raid', 'vik_race.raid_id', '=', 'vik_raid.raid_id')
            ->where('vik_race.race_id', $raceId)
            ->first();

        if (! $race) {
            return null;
        }

        $members = JoinRace::with(['member', 'team'])
            ->where('race_id', $raceId)
            ->get()
            ->groupBy('team_id');

        return ['race' => $race, 'members' => $members];
    }

    public function validateTeam(string $raceId, int $teamId)
    {
        return JoinRace::where('race_id', $raceId)
            ->where('team_id', $teamId)
            ->update([
                'jrace_payement_valid' => 1,
                'jrace_presence_valid' => 1,
            ]);
    }

    public function updateIndividualValidation(string $raceId, int $userId, string $field, $value)
    {
        return JoinRace::where('race_id', $raceId)
            ->where('user_id', $userId)
            ->update([$field => $value]);
    }
}
