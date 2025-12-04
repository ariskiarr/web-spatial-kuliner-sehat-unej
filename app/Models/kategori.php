<?php

namespace App\Models;

/**
 * Kategori Model - Inheritance dari BaseModel
 * Implementasi: Inheritance, Enkapsulasi, Overriding
 */
class kategori extends BaseModel
{
    // Enkapsulasi: protected table name
    protected $table = 'kategori';

    protected $fillable = [
        'nama_kategori',
    ];

    /**
     * Override getModelName - Polymorphism
     */
    public function getModelName(): string
    {
        return 'Kategori';
    }

    /**
     * Relationship with tempat_makan - Enkapsulasi
     */
    public function tempatMakan()
    {
        return $this->hasMany(tempat_makan::class, 'kategori_id');
    }

    /**
     * Get total tempat makan in this kategori
     */
    public function getTotalTempatMakan(): int
    {
        return $this->tempatMakan()->count();
    }

    /**
     * Override validateBeforeSave - Overriding
     */
    protected function validateBeforeSave(): bool
    {
        if (empty($this->nama_kategori)) {
            return false;
        }
        return parent::validateBeforeSave();
    }

    /**
     * Get kategori icon based on name - Enkapsulasi business logic
     */
    public function getIcon(): string
    {
        $icons = [
            'restoran' => '🍽️',
            'cafe' => '☕',
            'warung' => '🏪',
            'fast food' => '🍔',
            'street food' => '🌮',
            'bakery' => '🍞',
            'dessert' => '🍰',
            'seafood' => '🦞',
        ];

        $nama = strtolower($this->nama_kategori);

        foreach ($icons as $key => $icon) {
            if (strpos($nama, $key) !== false) {
                return $icon;
            }
        }

        return '🍴';
    }
}
