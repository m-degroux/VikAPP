<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class GeocodingService
{
    public function getCoordinates(string $address): ?array
    {
        $response = Http::get('https://api-adresse.data.gouv.fr/search/', [
            'q' => $address,
            'limit' => 1
        ]);

        if ($response->successful() && count($response->json('features')) > 0) {
            $feature = $response->json('features')[0];
            $coordinates = $feature['geometry']['coordinates'];

            return [
                'lat' => $coordinates[1],
                'lng' => $coordinates[0]
            ];
        }

        return null;
    }
}
