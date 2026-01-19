<?php

namespace App\Services;

use App\Models\Raid;

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

    public function searchRaids(array $filters)
    {
        $query = Raid::query();

        if (! empty($filters['lat']) && ! empty($filters['lon'])) {
            $radius = (int) ($filters['radius'] ?? 50);
            $lat = (float) $filters['lat'];
            $lon = (float) $filters['lon'];

            $query->selectRaw('*, (6371 * acos(cos(radians(?)) * cos(radians(RAID_LAT)) * cos(radians(RAID_LNG) - radians(?)) + sin(radians(?)) * sin(radians(RAID_LAT)))) AS distance',
                [$lat, $lon, $lat]
            )
                ->having('distance', '<=', $radius)
                ->orderBy('distance', 'asc');
        } elseif (! empty($filters['location'])) {
            $query->where('raid_place', 'LIKE', '%'.$filters['location'].'%');
        }

        return $query->with(['races', 'managers'])->get();
    }
}
