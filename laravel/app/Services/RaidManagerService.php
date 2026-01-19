<?php

namespace App\Services;

use App\Models\Club;
use App\Models\ManageRaid;
use App\Models\Raid;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class RaidManagerService
{
    public function getManagedRaids(int $userId)
    {
        return Raid::join('vik_manage_raid', 'vik_raid.raid_id', '=', 'vik_manage_raid.raid_id')
            ->where('vik_manage_raid.user_id', '=', $userId)
            ->select('vik_raid.*')
            ->orderBy('raid_start_date', 'asc')
            ->get();
    }

    public function getRaidForEdit(string $raidId)
    {
        $raid = Raid::findOrFail($raidId);
        $user = Auth::user();

        $isAdmin = Gate::allows('admin', $user);
        $isAssignedManager = ManageRaid::where('raid_id', $raidId)->where('user_id', $user->user_id)->exists();
        $isClubManagerOfRaid = $user->isClubManager() && optional($user->managedClub)->club_id == $raid->club_id;

        if (! ($isAdmin || $isAssignedManager || $isClubManagerOfRaid)) {
            abort(403, 'Accès refusé');
        }

        $clubMembers = collect();
        if ($raid->club_id) {
            $club = Club::with('members')->where('club_id', $raid->club_id)->first();
            $clubMembers = $club ? $club->members : collect();
        }

        return ['raid' => $raid, 'clubMembers' => $clubMembers];
    }

    public function updateRaid(string $raidId, array $data)
    {
        $raid = Raid::findOrFail($raidId);
        $user = Auth::user();

        $isAdmin = Gate::allows('admin', $user);
        $isAssignedManager = ManageRaid::where('raid_id', $raidId)->where('user_id', $user->user_id)->exists();
        $isClubManagerOfRaid = $user->isClubManager() && optional($user->managedClub)->club_id == $raid->club_id;

        if (! ($isAdmin || $isAssignedManager || $isClubManagerOfRaid)) {
            abort(403, 'Accès refusé');
        }

        $raid->fill(array_filter([
            'raid_name' => $data['raid_name'] ?? null,
            'raid_place' => $data['raid_place'] ?? $raid->raid_place,
            'raid_contact' => $data['raid_contact'] ?? $raid->raid_contact,
            'raid_website' => $data['raid_website'] ?? $raid->raid_website,
            'raid_reg_start_date' => $data['raid_reg_start_date'] ?? $raid->raid_reg_start_date,
            'raid_reg_end_date' => $data['raid_reg_end_date'] ?? $raid->raid_reg_end_date,
            'raid_start_date' => $data['raid_start_date'] ?? $raid->raid_start_date,
            'raid_end_date' => $data['raid_end_date'] ?? $raid->raid_end_date,
        ]));

        $raid->save();

        if (! empty($data['responsible_id'])) {
            DB::transaction(function () use ($raid, $data) {
                ManageRaid::where('raid_id', $raid->raid_id)->delete();
                ManageRaid::create([
                    'raid_id' => $raid->raid_id,
                    'user_id' => $data['responsible_id'],
                ]);
            });
        }

        return $raid;
    }
}
