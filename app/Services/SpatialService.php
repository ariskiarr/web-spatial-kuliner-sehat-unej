<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

/**
 * Service untuk operasi spasial - Encapsulation
 * Semua logika spatial di-encapsulate di sini
 */
class SpatialService
{
    /**
     * Calculate distance between two points using PostGIS
     *
     * @param float $lat1 Latitude point 1
     * @param float $lng1 Longitude point 1
     * @param float $lat2 Latitude point 2
     * @param float $lng2 Longitude point 2
     * @return float Distance in kilometers
     */
    public function calculateDistance(float $lat1, float $lng1, float $lat2, float $lng2): float
    {
        $result = DB::selectOne("
            SELECT ST_DistanceSphere(
                ST_MakePoint(?, ?),
                ST_MakePoint(?, ?)
            ) / 1000 as distance
        ", [$lng1, $lat1, $lng2, $lat2]);

        return round($result->distance, 2);
    }

    /**
     * Get tempat makan within radius from a point
     *
     * @param float $lat Center latitude
     * @param float $lng Center longitude
     * @param float $radiusKm Radius in kilometers
     * @return \Illuminate\Support\Collection
     */
    public function getTempatMakanWithinRadius(float $lat, float $lng, float $radiusKm)
    {
        return DB::select("
            SELECT
                t.*,
                ST_X(t.geom) as longitude,
                ST_Y(t.geom) as latitude,
                ST_DistanceSphere(
                    t.geom,
                    ST_MakePoint(?, ?)
                ) / 1000 as distance_km
            FROM tempat_makan t
            WHERE ST_DWithin(
                t.geom::geography,
                ST_MakePoint(?, ?)::geography,
                ? * 1000
            )
            ORDER BY distance_km ASC
        ", [$lng, $lat, $lng, $lat, $radiusKm]);
    }

    /**
     * Create buffer (radius) around a point
     * Returns GeoJSON for visualization
     */
    public function createBuffer(float $lat, float $lng, float $radiusKm): array
    {
        $result = DB::selectOne("
            SELECT ST_AsGeoJSON(
                ST_Buffer(
                    ST_MakePoint(?, ?)::geography,
                    ? * 1000
                )::geometry
            ) as geojson
        ", [$lng, $lat, $radiusKm]);

        return json_decode($result->geojson, true);
    }
}
