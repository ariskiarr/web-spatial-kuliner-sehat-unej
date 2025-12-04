<?php

namespace App\Models;

use App\Contracts\KaloriCalculable;

/**
 * MenuMakan Model
 * Implementasi: Inheritance, Polymorphism, Enkapsulasi, Interface Implementation
 */
class menu_makan extends BaseModel implements KaloriCalculable
{
    protected $table = 'menu_makan';
    
    // Enkapsulasi: protected properties
    protected $fillable = [
        'tempat_id',
        'nama_menu',
        'harga',
        'kalori'
    ];

    // Enkapsulasi: private property for calorie database
    private static array $calorieDatabase = [
        // Nasi varieties
        'nasi putih' => 200,
        'nasi goreng' => 450,
        'nasi kuning' => 300,
        'nasi uduk' => 350,
        'nasi liwet' => 280,
        
        // Mie varieties
        'mie goreng' => 400,
        'mie ayam' => 380,
        'mie rebus' => 320,
        
        // Ayam dishes
        'ayam goreng' => 500,
        'ayam bakar' => 450,
        'ayam rica' => 480,
        'ayam geprek' => 550,
        
        // Seafood
        'ikan bakar' => 300,
        'ikan goreng' => 350,
        'udang goreng' => 400,
        'cumi goreng' => 380,
        
        // Daging
        'sate ayam' => 420,
        'sate kambing' => 500,
        'rendang' => 600,
        'gulai' => 550,
        
        // Minuman
        'es teh' => 80,
        'es jeruk' => 100,
        'jus alpukat' => 250,
        'kopi' => 50,
        'air mineral' => 0,
        
        // Dessert
        'es krim' => 300,
        'brownies' => 400,
        'pancake' => 350,
    ];

    /**
     * Override getModelName - Polymorphism
     */
    public function getModelName(): string
    {
        return 'Menu Makan';
    }

    /**
     * Relationship with tempat_makan - Enkapsulasi
     */
    public function tempat_makan()
    {
        return $this->belongsTo(tempat_makan::class, 'tempat_id');
    }

    /**
     * Override validateBeforeSave - Overriding
     */
    protected function validateBeforeSave(): bool
    {
        if (empty($this->nama_menu) || $this->harga <= 0) {
            return false;
        }
        return parent::validateBeforeSave();
    }

    /**
     * Estimate calories from food name - Enkapsulasi business logic
     * Algorithm with 3-level matching
     */
    public function estimateCaloriesFromName(string $foodName = null): int
    {
        $name = strtolower($foodName ?? $this->nama_menu);
        
        // Level 1: Exact match
        if (isset(self::$calorieDatabase[$name])) {
            return self::$calorieDatabase[$name];
        }
        
        // Level 2: Keyword match
        foreach (self::$calorieDatabase as $key => $calories) {
            if (str_contains($name, $key) || str_contains($key, $name)) {
                return $calories;
            }
        }
        
        // Level 3: Category-based estimation
        $categoryCalories = [
            'nasi' => 350,
            'mie' => 380,
            'ayam' => 480,
            'ikan' => 320,
            'udang' => 400,
            'daging' => 550,
            'sate' => 450,
            'goreng' => 400,
            'bakar' => 350,
            'rebus' => 300,
            'es' => 150,
            'jus' => 200,
            'kopi' => 50,
            'teh' => 80,
        ];
        
        foreach ($categoryCalories as $keyword => $calories) {
            if (str_contains($name, $keyword)) {
                return $calories;
            }
        }
        
        // Default fallback
        return 250;
    }

    /**
     * Implement KaloriCalculable interface - Polymorphism
     */
    public function calculateTotalKalori(): int
    {
        return (int) $this->kalori;
    }

    /**
     * Implement KaloriCalculable interface - Polymorphism
     */
    public function getKaloriCategory(): string
    {
        $kalori = $this->calculateTotalKalori();
        
        if ($kalori < 200) {
            return 'Rendah';
        } elseif ($kalori < 400) {
            return 'Sedang';
        } else {
            return 'Tinggi';
        }
    }

    /**
     * Implement KaloriCalculable interface - Polymorphism
     */
    public function isHealthy(): bool
    {
        return $this->calculateTotalKalori() <= 500;
    }

    /**
     * Get formatted price - Enkapsulasi
     */
    public function getFormattedPrice(): string
    {
        return 'Rp ' . number_format($this->harga, 0, ',', '.');
    }

    /**
     * Get health badge - Enkapsulasi business logic
     */
    public function getHealthBadge(): string
    {
        if ($this->isHealthy()) {
            return '✓ Sehat';
        }
        return '⚠ Perhatian Kalori';
    }

    /**
     * Get calorie badge color - Enkapsulasi
     */
    public function getCalorieBadgeColor(): string
    {
        $category = $this->getKaloriCategory();
        
        return match($category) {
            'Rendah' => 'green',
            'Sedang' => 'orange',
            'Tinggi' => 'red',
            default => 'gray'
        };
    }

    /**
     * Get price range category - Enkapsulasi business logic
     */
    public function getPriceCategory(): string
    {
        if ($this->harga < 15000) {
            return 'Murah';
        } elseif ($this->harga < 50000) {
            return 'Sedang';
        } else {
            return 'Mahal';
        }
    }

    /**
     * Check if affordable - Overloading concept
     */
    public function isAffordable($budget = 30000): bool
    {
        return $this->harga <= $budget;
    }

    /**
     * Get menu summary - Enkapsulasi
     */
    public function getSummary(): array
    {
        return [
            'nama' => $this->nama_menu,
            'harga' => $this->getFormattedPrice(),
            'kalori' => $this->calculateTotalKalori(),
            'kategori_kalori' => $this->getKaloriCategory(),
            'kategori_harga' => $this->getPriceCategory(),
            'sehat' => $this->isHealthy(),
            'badge_color' => $this->getCalorieBadgeColor(),
        ];
    }
}
