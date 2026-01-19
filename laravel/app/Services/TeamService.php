<?php

namespace App\Services;

use App\Models\JoinTeam;
use Illuminate\Support\Facades\DB;

class TeamService
{
    public function joinTeam(int $userId, int $teamId)
    {
        return DB::table('vik_join_team')->updateOrInsert(
            ['user_id' => $userId, 'team_id' => $teamId],
            ['user_id' => $userId, 'team_id' => $teamId]
        );
    }

    public function removeMemberFromTeam(int $userId, int $teamId)
    {
        return JoinTeam::where('user_id', $userId)->where('team_id', $teamId)->delete();
    }
}
