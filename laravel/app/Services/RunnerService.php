<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class RunnerService
{
    public function getGlobalStatistics(int $userId): array
    {
        $nbCourses = DB::table('vik_join_race')->where('user_id', $userId)->count();
        $totalPoints = DB::table('vik_team')->where('user_id', $userId)->sum('team_point');

        return [
            'nbCourses' => $nbCourses,
            'totalPoints' => $totalPoints,
        ];
    }

    public function getUpcomingRaces(int $userId): Collection
    {
        $now = Carbon::now();

        return DB::table('vik_race as r')
            ->join('vik_raid as ra', 'ra.raid_id', '=', 'r.raid_id')
            ->join('vik_team as t', 't.race_id', '=', 'r.race_id')
            ->where('r.race_start_date', '>', $now)
            ->where(function ($q) use ($userId) {
                $q->where('t.user_id', $userId)
                    ->orWhereExists(function ($sub) use ($userId) {
                        $sub->select(DB::raw(1))
                            ->from('vik_join_race as jr')
                            ->whereColumn('jr.team_id', 't.team_id')
                            ->whereColumn('jr.race_id', 'r.race_id')
                            ->where('jr.user_id', $userId);
                    });
            })
            ->selectRaw("
                r.race_name,
                r.race_start_date,
                r.race_length,
                ra.raid_name,
                t.team_name,
                t.team_id,
                CASE
                    WHEN t.user_id = ?
                    AND EXISTS (
                        SELECT 1 FROM vik_join_race jr
                        WHERE jr.user_id = ?
                        AND jr.team_id = t.team_id
                        AND jr.race_id = r.race_id
                    )
                        THEN 'manager/coureur'
                    WHEN t.user_id = ?
                        THEN 'manager'
                    WHEN EXISTS (
                        SELECT 1 FROM vik_join_race jr
                        WHERE jr.user_id = ?
                        AND jr.team_id = t.team_id
                        AND jr.race_id = r.race_id
                    )
                        THEN 'coureur'
                    ELSE 'aucun'
                END AS statut
            ", [$userId, $userId, $userId, $userId])
            ->orderBy('r.race_start_date', 'asc')
            ->get();
    }

    public function getRaceHistory(int $userId): Collection
    {
        $now = Carbon::now();

        return DB::table('vik_race as r')
            ->join('vik_raid as ra', 'ra.raid_id', '=', 'r.raid_id')
            ->join('vik_team as t', 't.race_id', '=', 'r.race_id')
            ->where('r.race_start_date', '<', $now)
            ->where(function ($q) use ($userId) {
                $q->where('t.user_id', $userId)
                    ->orWhereExists(function ($sub) use ($userId) {
                        $sub->select(DB::raw(1))
                            ->from('vik_join_race as jr')
                            ->whereColumn('jr.team_id', 't.team_id')
                            ->whereColumn('jr.race_id', 'r.race_id')
                            ->where('jr.user_id', $userId);
                    });
            })
            ->selectRaw("
                r.race_id,
                r.race_name,
                ra.raid_name,
                t.team_id,
                t.user_id,
                t.team_name,
                t.team_picture,
                t.team_time,
                t.team_point,
                CASE
                    WHEN t.user_id = ?
                    AND EXISTS (
                        SELECT 1 FROM vik_join_race jr
                        WHERE jr.user_id = ?
                        AND jr.team_id = t.team_id
                        AND jr.race_id = r.race_id
                    )
                        THEN 'manager/coureur'
                    WHEN t.user_id = ?
                        THEN 'manager'
                    WHEN EXISTS (
                        SELECT 1 FROM vik_join_race jr
                        WHERE jr.user_id = ?
                        AND jr.team_id = t.team_id
                        AND jr.race_id = r.race_id
                    )
                        THEN 'coureur'
                    ELSE 'aucun'
                END AS statut
            ", [$userId, $userId, $userId, $userId])
            ->orderBy('r.race_start_date', 'asc')
            ->get();
    }

    public function processRaceHistory(Collection $history): array
    {
        $nbPodiums = 0;

        foreach ($history as $item) {
            $item->total_participants = DB::table('vik_team')
                ->where('race_id', $item->race_id)
                ->count();

            if ($item->team_time !== null) {
                $betterTeams = DB::table('vik_team')
                    ->where('race_id', $item->race_id)
                    ->whereNotNull('team_time')
                    ->where('team_time', '<', $item->team_time)
                    ->count();

                $item->rank = $betterTeams + 1;
            } else {
                $item->rank = '-';
            }

            if ($item->rank !== '-' && $item->rank <= 3) {
                $nbPodiums++;
            }
        }

        return [
            'history' => $history,
            'nbPodiums' => $nbPodiums,
        ];
    }
}
