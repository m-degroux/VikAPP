<?php

namespace App\Services;

use App\Models\ManageRaid;
use App\Models\Raid;
use App\Models\Club;
use Illuminate\Support\Facades\Auth;

class RaidService
{
    public function getAllRaids()
    {
        return Raid::with(['races', 'managers'])->get();
    }

    public function getRaidById(string $raidId)
    {
        return Raid::with(['races', 'managers'])->where('raid_id', $raidId)->firstOrFail();
    }

    public function getUpcomingRaids($nb)
    {
        return Raid::with(['races', 'managers'])
            ->where('raid_start_date', '>=', now())
            ->orderBy('raid_start_date', 'asc')
            ->take($nb)
            ->get();
    }

    public function createRaid(array $data, $responsibleId)
    {
        if ($data['raid_start_date'] > $data['raid_end_date']) {
            throw new \Exception("La date de début ne peut pas être après la date de fin.");
        }
        if ($data['raid_reg_start_date'] > $data['raid_reg_end_date']) {
            throw new \Exception("La date d'ouverture des inscriptions est incohérente.");
        }
        $data['status'] = 'draft';

        $club = Club::select('club_id')->where('user_id', Auth::user()->user_id)->first();

        $data['club_id'] = $club->club_id;

        try {
            return \DB::transaction(function () use ($data, $responsibleId) {
                $raid = Raid::create($data);
                
                ManageRaid::create([
                    'raid_id' => $raid->raid_id,
                    'user_id' => $responsibleId,
                ]);

                return $raid;
            });
        } catch (\Exception $e) {
            \Log::error("Erreur lors de la création du Raid : " . $e->getMessage());
            throw $e;
        }
    }

    public function searchRaids(array $filters)
    {
        $query = Raid::query();

        if (!empty($filters['lat']) && !empty($filters['lon'])) {
            $radius = (int)($filters['radius'] ?? 50);
            $lat = (float)$filters['lat'];
            $lon = (float)$filters['lon'];

            $query->selectRaw("*, (6371 * acos(cos(radians(?)) * cos(radians(RAID_LAT)) * cos(radians(RAID_LNG) - radians(?)) + sin(radians(?)) * sin(radians(RAID_LAT)))) AS distance",
                [$lat, $lon, $lat]
            )
                ->having('distance', '<=', $radius)
                ->orderBy('distance', 'asc');
        } elseif (!empty($filters['location'])) {
            $query->where('raid_place', 'LIKE', '%' . $filters['location'] . '%');
        }

        return $query->with(['races', 'managers'])->get();
    }

}
