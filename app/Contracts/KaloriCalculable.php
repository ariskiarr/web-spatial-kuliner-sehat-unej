<?php

namespace App\Contracts;

/**
 * Interface untuk Kalori Calculation - Polymorphism
 * Implementasi akan berbeda untuk setiap class
 */
interface KaloriCalculable
{
    /**
     * Hitung total kalori
     */
    public function calculateTotalKalori(): int;

    /**
     * Get kategori kalori (Low, Medium, High)
     */
    public function getKaloriCategory(): string;

    /**
     * Check apakah masuk kategori sehat
     */
    public function isHealthy(): bool;
}
