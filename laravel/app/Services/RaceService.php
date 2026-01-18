<?php

namespace App\Services;

use App\Models\Race;

class RaceService
{
    public function getAll()
    {
        return Race::all();
    }

    public function create(array $data)
    {
        return Race::create($data);
    }

    public function getById($id)
    {
        return Race::find($id);
    }

    public function update($id, array $data)
    {
        $race = Race::find($id);
        if ($race) {
            $race->update($data);
            return $race;
        }
        return null;
    }

    /**
     * register a user to a race
     */
    public function registerToRace(array $data)
    {
        return DB::table('vik_join_race')->insert([
            'user_id' => $data['user_id'],
            'race_id' => $data['race_id'],
            'team_id' => $data['team_id'],
            'jrace_licence_num' => $data['jrace_licence_num'] ?? null,
            'jrace_pps' => $data['jrace_pps'] ?? null,
            'jrace_presence_valid' => false,
            'jrace_payement_valid' => false,
        ]);
    }
}
