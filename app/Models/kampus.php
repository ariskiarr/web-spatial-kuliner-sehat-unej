<?php

namespace App\Models;

use App\Contracts\Geolocatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;

/**
 * Kampus Model
 * Implementasi: Inheritance, Polymorphism, Enkapsulasi, Interface Implementation
 */
class kampus extends BaseModel implements Geolocatable
{
    use HasFactory;
    
    protected $table = 'kampus';
    public $timestamps = false;
    
    protected $fillable = [
        'nama_kampus',
        'alamat',
        'geom'
    ];

    // Enkapsulasi: private properties for coordinates
    private ?float $cachedLatitude = null;
    private ?float $cachedLongitude = null;

    /**
     * Override getModelName - Polymorphism
     */
    public function getModelName(): string
    {
        return 'Kampus';
    }

    /**
     * Implementation of Geolocatable interface
     */
    public function getLatitude(): ?float
    {
        if ($this->cachedLatitude !== null) {
            return $this->cachedLatitude;
        }

        if (!$this->geom) {
            return null;
        }

        try {
            $result = DB::selectOne("SELECT ST_Y(geom::geometry) as lat FROM {$this->table} WHERE id = ?", [$this->id]);
            $this->cachedLatitude = $result ? (float) $result->lat : null;
            return $this->cachedLatitude;
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Implementation of Geolocatable interface
     */
    public function getLongitude(): ?float
    {
        if ($this->cachedLongitude !== null) {
            return $this->cachedLongitude;
        }

        if (!$this->geom) {
            return null;
        }

        try {
            $result = DB::selectOne("SELECT ST_X(geom::geometry) as lon FROM {$this->table} WHERE id = ?", [$this->id]);
            $this->cachedLongitude = $result ? (float) $result->lon : null;
            return $this->cachedLongitude;
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Calculate distance to another location (in km)
     */
    public function distanceTo(Geolocatable $location): float
    {
        $lat1 = $this->getLatitude();
        $lon1 = $this->getLongitude();
        $lat2 = $location->getLatitude();
        $lon2 = $location->getLongitude();

        if (!$lat1 || !$lon1 || !$lat2 || !$lon2) {
            return 0;
        }

        try {
            $result = DB::selectOne("
                SELECT ST_DistanceSphere(
                    ST_MakePoint(?, ?)::geography,
                    ST_MakePoint(?, ?)::geography
                ) / 1000 as distance
            ", [$lon1, $lat1, $lon2, $lat2]);

            return $result ? (float) $result->distance : 0;
        } catch (\Exception $e) {
            return 0;
        }
    }

    /**
     * Check if location is within radius (in km)
     */
    public function isWithinRadius(Geolocatable $location, float $radius): bool
    {
        return $this->distanceTo($location) <= $radius;
    }
}
