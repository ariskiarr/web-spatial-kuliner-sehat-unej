<?php

namespace App\Services;

use App\Models\tempat_makan;
use App\Models\menu_makan;
use Illuminate\Support\Facades\DB;

/**
 * Service untuk operasi kalori - Business Logic Encapsulation
 */
class KaloriService
{
    /**
     * Get tempat makan dengan rata-rata kalori terendah
     *
     * @param int $limit
     * @return \Illuminate\Support\Collection
     */
    public function getTempatMakanByLowestCalories(int $limit = 10)
    {
        return DB::table('tempat_makan as t')
            ->select([
                't.*',
                DB::raw('AVG(m.kalori) as avg_kalori'),
                DB::raw('MIN(m.kalori) as min_kalori'),
                DB::raw('ST_X(t.geom) as longitude'),
                DB::raw('ST_Y(t.geom) as latitude')
            ])
            ->join('menu_makan as m', 't.id', '=', 'm.tempat_id')
            ->whereNotNull('m.kalori')
            ->groupBy('t.id')
            ->orderBy('avg_kalori', 'ASC')
            ->limit($limit)
            ->get();
    }

    /**
     * Get tempat makan dengan kalori terendah yang paling dekat dari kampus
     *
     * @param float $kampusLat
     * @param float $kampusLng
     * @param float $maxCalories
     * @param float $radiusKm
     * @return \Illuminate\Support\Collection
     */
    public function getTempatMakanLowCaloriesNearKampus(
        float $kampusLat,
        float $kampusLng,
        float $maxCalories = 500,
        float $radiusKm = 5
    ) {
        return DB::select("
            WITH tempat_avg_kalori AS (
                SELECT
                    t.id,
                    t.nama_tempat,
                    t.alamat,
                    t.kategori_id,
                    t.jam_operasional,
                    ST_X(t.geom::geometry) as lng,
                    ST_Y(t.geom::geometry) as lat,
                    AVG(m.kalori) as avg_kalori,
                    MIN(m.kalori) as min_kalori,
                    MAX(m.kalori) as max_kalori,
                    COUNT(m.id) as total_menu,
                    ST_DistanceSphere(
                        t.geom,
                        ST_MakePoint(?, ?)
                    ) / 1000 as distance_km
                FROM tempat_makan t
                INNER JOIN menu_makan m ON t.id = m.tempat_id
                WHERE m.kalori IS NOT NULL
                AND ST_DWithin(
                    t.geom::geography,
                    ST_MakePoint(?, ?)::geography,
                    ? * 1000
                )
                GROUP BY t.id, t.geom
                HAVING AVG(m.kalori) <= ?
            )
            SELECT ta.*, k.nama_kategori
            FROM tempat_avg_kalori ta
            LEFT JOIN kategori k ON ta.kategori_id = k.id
            ORDER BY avg_kalori ASC, distance_km ASC
        ", [$kampusLng, $kampusLat, $kampusLng, $kampusLat, $radiusKm, $maxCalories]);
    }

    /**
     * Get menu by kategori kalori
     *
     * @param string $kategori 'rendah' | 'sedang' | 'tinggi'
     * @return \Illuminate\Support\Collection
     */
    public function getMenuByKategoriKalori(string $kategori)
    {
        $ranges = [
            'rendah' => [0, 300],
            'sedang' => [301, 600],
            'tinggi' => [601, 9999]
        ];

        [$min, $max] = $ranges[$kategori] ?? [0, 9999];

        return menu_makan::whereBetween('kalori', [$min, $max])
            ->with('tempatMakan')
            ->orderBy('kalori', 'ASC')
            ->get();
    }

    /**
     * Get statistik kalori per kategori tempat makan
     */
    public function getKaloriStatsByKategori()
    {
        return DB::table('tempat_makan as t')
            ->select([
                'k.nama_kategori',
                DB::raw('AVG(m.kalori) as avg_kalori'),
                DB::raw('MIN(m.kalori) as min_kalori'),
                DB::raw('MAX(m.kalori) as max_kalori'),
                DB::raw('COUNT(DISTINCT t.id) as total_tempat')
            ])
            ->join('kategori as k', 't.kategori_id', '=', 'k.id')
            ->join('menu_makan as m', 't.id', '=', 'm.tempat_id')
            ->whereNotNull('m.kalori')
            ->groupBy('k.id', 'k.nama_kategori')
            ->get();
    }
}
