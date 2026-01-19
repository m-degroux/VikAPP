<?php

namespace App\Contracts;

interface GeocodingServiceInterface
{
    /**
     * @return array{lat: float, lng: float}|null
     */
    public function geocode(string $address): ?array;
}
