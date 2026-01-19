<?php

namespace App\Console\Commands;

use App\Models\Raid;
use App\Services\GeocodingService;
use Illuminate\Console\Command;

class GeocodeRaids extends Command
{
    /**
     * Le nom de la commande à taper dans le terminal
     */
    protected $signature = 'raids:geocode';

    /**
     * La description qui s'affiche quand on fait php artisan list
     */
    protected $description = 'Calcule les coordonnées GPS pour tous les raids à partir de leur champ raid_place';

    /**
     * L'exécution de la commande
     */
    public function handle(GeocodingService $geocoder)
    {
        $raids = Raid::whereNotNull('raid_place')
            ->where(function ($query) {
                $query->whereNull('raid_lat')
                    ->orWhereNull('raid_lng');
            })
            ->get();

        if ($raids->isEmpty()) {
            $this->info('Aucun raid à géocoder.');

            return;
        }

        $this->info("Traitement de {$raids->count()} raids...");

        foreach ($raids as $raid) {
            $this->comment("Géocodage de : {$raid->raid_name} ({$raid->raid_place})...");

            $coords = $geocoder->getCoordinates($raid->raid_place);

            if ($coords) {
                $raid->update([
                    'raid_lat' => $coords['lat'],
                    'raid_lng' => $coords['lng'],
                ]);
                $this->info('✅ Succès !');
            } else {
                $this->error("❌ Impossible de trouver les coordonnées pour l'adresse : {$raid->raid_place}");
            }

            usleep(200000);
        }

        $this->info('Opération terminée.');
    }
}
