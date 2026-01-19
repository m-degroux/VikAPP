<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Club;
use App\Models\Member;
use App\Models\Race;
use App\Models\Raid;

class DashboardController extends Controller
{
    public function index()
    {
        $raidsCount = Raid::count();
        $racesCount = Race::count();
        $clubsCount = Club::count();
        $membersCount = Member::count();

        // Derniers raids créés (utiliser raid_start_date au lieu de created_at)
        $recentRaids = Raid::with('club')
            ->orderBy('raid_start_date', 'desc')
            ->limit(5)
            ->get();

        // Activité récente : derniers raids et courses créés
        $recentActivity = collect();
        
        // Ajouter les derniers raids
        $latestRaids = Raid::with('club')
            ->orderBy('raid_start_date', 'desc')
            ->limit(3)
            ->get()
            ->map(function ($raid) {
                return [
                    'type' => 'raid',
                    'title' => $raid->raid_name,
                    'description' => 'Raid programmé',
                    'date' => $raid->raid_start_date,
                    'club' => $raid->club?->club_name ?? 'N/A',
                ];
            });

        // Ajouter les dernières courses
        $latestRaces = Race::with('raid')
            ->orderBy('race_start_date', 'desc')
            ->limit(3)
            ->get()
            ->map(function ($race) {
                return [
                    'type' => 'race',
                    'title' => $race->race_name,
                    'description' => 'Course programmée',
                    'date' => $race->race_start_date,
                    'raid' => $race->raid?->raid_name ?? 'N/A',
                ];
            });

        // Fusionner et trier par date
        $recentActivity = $latestRaids->concat($latestRaces)
            ->sortByDesc('date')
            ->take(5);

        return view('admin.dashboard', compact(
            'raidsCount',
            'racesCount',
            'clubsCount',
            'membersCount',
            'recentRaids',
            'recentActivity'
        ));
    }
}
