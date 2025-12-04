<?php

namespace App\Contracts;

/**
 * Interface untuk Geocoding - Polymorphism
 */
interface Geolocatable
{
    /**
     * Get koordinat latitude
     */
    public function getLatitude(): ?float;

    /**
     * Get koordinat longitude
     */
    public function getLongitude(): ?float;

    /**
     * Calculate distance to another location (in km)
     */
    public function distanceTo(Geolocatable $location): float;

    /**
     * Check if location is within radius (in km)
     */
    public function isWithinRadius(Geolocatable $location, float $radius): bool;
}
