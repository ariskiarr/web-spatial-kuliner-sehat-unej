<?php

namespace App\Contracts;

/**
 * Interface untuk filtering - Abstraction
 */
interface Filterable
{
    /**
     * Filter by calorie range
     */
    public function filterByCalories(float $min, float $max);

    /**
     * Filter by distance from point
     */
    public function filterByDistance(float $lat, float $lng, float $radiusKm);

    /**
     * Filter by kategori
     */
    public function filterByKategori(int $kategoriId);
}
