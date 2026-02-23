<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Ulasan Model
 * Implementasi: Inheritance, Polymorphism, Encapsulation
 */
class ulasan extends BaseModel
{
    use HasFactory;
    
    protected $table = 'ulasan';
    public $timestamps = false;
    
    // Encapsulation: protected properties
    protected $fillable = [
        'user_id',
        'tempat_id',
        'rating',
        'komentar',
        'sumber',
        'penulis',
        'ulasan',
        'tanggal',
    ];

    /**
     * Polymorphism: Override abstract method
     */
    public function getModelName(): string
    {
        return 'Ulasan';
    }

    /**
     * Relationship with User - Encapsulation
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relationship with tempat_makan - Encapsulation
     */
    public function tempatMakan()
    {
        return $this->belongsTo(tempat_makan::class, 'tempat_id');
    }

    /**
     * Legacy method for backward compatibility
     */
    public function tempat_makan()
    {
        return $this->tempatMakan();
    }

    /**
     * Get star rating as HTML - Encapsulation
     */
    public function getStarRatingHtml(): string
    {
        $fullStars = floor($this->rating);
        $halfStar = ($this->rating - $fullStars) >= 0.5 ? 1 : 0;
        $emptyStars = 5 - $fullStars - $halfStar;

        $html = str_repeat('⭐', $fullStars);
        if ($halfStar) $html .= '⭐';
        
        return $html;
    }

    /**
     * Check if review is positive (>= 4 stars)
     */
    public function isPositive(): bool
    {
        return $this->rating >= 4;
    }

    /**
     * Get rating category
     */
    public function getRatingCategory(): string
    {
        if ($this->rating >= 4.5) return 'Excellent';
        if ($this->rating >= 3.5) return 'Good';
        if ($this->rating >= 2.5) return 'Average';
        if ($this->rating >= 1.5) return 'Poor';
        return 'Very Poor';
    }

    /**
     * Get formatted date - Encapsulation
     */
    public function getFormattedDate(): string
    {
        if ($this->tanggal) {
            return date('d F Y', strtotime($this->tanggal));
        }
        return date('d F Y');
    }

    /**
     * Scope for recent reviews
     */
    public function scopeRecent($query, $days = 30)
    {
        return $query->where('tanggal', '>=', now()->subDays($days));
    }

    /**
     * Scope for high ratings
     */
    public function scopeHighRating($query, $minRating = 4)
    {
        return $query->where('rating', '>=', $minRating);
    }
}
