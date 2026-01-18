<?php

namespace App\Services;

use App\Models\JoinTeam;

class JoinTeamService
{
    public function join(array $data)
    {
        $exists = JoinTeam::where('team_id', $data['team_id'])
            ->where('user_id', $data['user_id'])
            ->first();

        if ($exists) {
            return $exists;
        }

        return JoinTeam::create($data);
    }

    public function leave(int $teamId, int $userId)
    {
        return JoinTeam::where('team_id', $teamId)
            ->where('user_id', $userId)
            ->delete();
    }

    public function getMembersByTeam(int $teamId)
    {
        return JoinTeam::where('team_id', $teamId)->get();
    }
}
