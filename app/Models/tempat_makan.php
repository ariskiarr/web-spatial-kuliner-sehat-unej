<?php

namespace App\Models;

use App\Contracts\Geolocatable;
use Illuminate\Support\Facades\DB;

/**
 * TempatMakan Model
 * Implementasi: Inheritance, Polymorphism, Enkapsulasi, Interface Implementation
 */
class tempat_makan extends BaseModel implements Geolocatable
{
    protected $table = 'tempat_makan';

    // Enkapsulasi: protected properties
    protected $fillable = [
        'nama_tempat',
        'kategori_id',
        'alamat',
        'jam_operasional',
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
        return 'Tempat Makan';
    }

    /**
     * Relationship with kategori - Enkapsulasi
     */
    public function kategori()
    {
        return $this->belongsTo(kategori::class, 'kategori_id');
    }

    /**
     * Relationship with menu_makan - Enkapsulasi
     */
    public function menus()
    {
        return $this->hasMany(menu_makan::class, 'tempat_id');
    }

    /**
     * Override validateBeforeSave - Overriding
     */
    protected function validateBeforeSave(): bool
    {
        if (empty($this->nama_tempat) || empty($this->kategori_id)) {
            return false;
        }
        return parent::validateBeforeSave();
    }

    /**
     * Implement Geolocatable interface - Polymorphism
     */
    public function getLatitude(): ?float
    {
        if ($this->cachedLatitude === null) {
            $this->loadCoordinates();
        }
        return $this->cachedLatitude;
    }

    /**
     * Implement Geolocatable interface - Polymorphism
     */
    public function getLongitude(): ?float
    {
        if ($this->cachedLongitude === null) {
            $this->loadCoordinates();
        }
        return $this->cachedLongitude;
    }

    /**
     * Load coordinates from PostGIS - Enkapsulasi
     */
    private function loadCoordinates(): void
    {
        if ($this->id) {
            $result = DB::table($this->table)
                ->select(
                    DB::raw('ST_X(geom) as longitude'),
                    DB::raw('ST_Y(geom) as latitude')
                )
                ->where('id', $this->id)
                ->first();

            if ($result) {
                $this->cachedLatitude = (float) $result->latitude;
                $this->cachedLongitude = (float) $result->longitude;
            }
        }
    }

    /**
     * Calculate distance using Haversine formula - Polymorphism
     */
    public function distanceTo(Geolocatable $location): float
    {
        $lat1 = $this->getLatitude();
        $lon1 = $this->getLongitude();
        $lat2 = $location->getLatitude();
        $lon2 = $location->getLongitude();

        if ($lat1 === null || $lon1 === null || $lat2 === null || $lon2 === null) {
            return 0;
        }

        $earthRadius = 6371; // km

        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat/2) * sin($dLat/2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($dLon/2) * sin($dLon/2);

        $c = 2 * atan2(sqrt($a), sqrt(1-$a));
        $distance = $earthRadius * $c;

        return round($distance, 2);
    }

    /**
     * Check if within radius - Polymorphism
     */
    public function isWithinRadius(Geolocatable $location, float $radius): bool
    {
        return $this->distanceTo($location) <= $radius;
    }

    /**
     * Get total menu count - Enkapsulasi
     */
    public function getTotalMenus(): int
    {
        return $this->menus()->count();
    }

    /**
     * Get average menu price - Enkapsulasi business logic
     */
    public function getAverageMenuPrice(): float
    {
        return $this->menus()->avg('harga') ?? 0;
    }

    /**
     * Get total kalori of all menus - Enkapsulasi
     */
    public function getTotalKaloriAllMenus(): int
    {
        return $this->menus()->sum('kalori') ?? 0;
    }

    /**
     * Check if open now - Enkapsulasi business logic
     */
    public function isOpenNow(): bool
    {
        if (empty($this->jam_operasional)) {
            return true; // Assume open if no schedule
        }

        // Simple check - can be enhanced
        return true;
    }

    /**
     * Get status badge - Enkapsulasi
     */
    public function getStatusBadge(): string
    {
        return $this->isOpenNow() ? 'Buka' : 'Tutup';
    }

    /**
     * Get full address with coordinates - Overloading concept
     */
    public function getFullAddress($includeCoordinates = false): string
    {
        $address = $this->alamat;

        if ($includeCoordinates && $this->getLatitude() && $this->getLongitude()) {
            $address .= sprintf(
                " (%.6f, %.6f)",
                $this->getLatitude(),
                $this->getLongitude()
            );
        }

        return $address;
    }
}
