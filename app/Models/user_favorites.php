<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;

/**
 * UserFavorites Model
 * Implementasi: Inheritance, Polymorphism, Encapsulation
 */
class user_favorites extends BaseModel
{
    use HasFactory;
    
    protected $table = 'user_favorites';
    public $timestamps = true;
    
    // Encapsulation: protected properties
    protected $fillable = [
        'user_id',
        'tempat_makan_id',
        'tempat_id',
        'catatan',
        'created_at',
        'updated_at'
    ];

    /**
     * Polymorphism: Override abstract method
     */
    public function getModelName(): string
    {
        return 'User Favorite';
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
        // Support both column names
        $columnName = $this->attributes['tempat_makan_id'] ?? 
                      $this->attributes['tempat_id'] ?? 
                      'tempat_makan_id';
        
        return $this->belongsTo(tempat_makan::class, $columnName);
    }

    /**
     * Legacy method for backward compatibility
     */
    public function tempat_makan()
    {
        return $this->tempatMakan();
    }

    /**
     * Get how long ago this was favorited
     */
    public function getFavoritedAgo(): string
    {
        if (!$this->created_at) {
            return 'Recently';
        }

        $createdAt = Carbon::parse($this->created_at);
        $now = Carbon::now();
        
        $diffInDays = $createdAt->diffInDays($now);
        
        if ($diffInDays == 0) {
            $diffInHours = $createdAt->diffInHours($now);
            if ($diffInHours == 0) {
                return 'Just now';
            }
            return $diffInHours . ' hours ago';
        } elseif ($diffInDays < 7) {
            return $diffInDays . ' days ago';
        } elseif ($diffInDays < 30) {
            $weeks = floor($diffInDays / 7);
            return $weeks . ' week' . ($weeks > 1 ? 's' : '') . ' ago';
        } elseif ($diffInDays < 365) {
            $months = floor($diffInDays / 30);
            return $months . ' month' . ($months > 1 ? 's' : '') . ' ago';
        } else {
            $years = floor($diffInDays / 365);
            return $years . ' year' . ($years > 1 ? 's' : '') . ' ago';
        }
    }

    /**
     * Check if favorite is recent (within last 7 days)
     */
    public function isRecent(): bool
    {
        if (!$this->created_at) {
            return true;
        }
        
        return Carbon::parse($this->created_at)->diffInDays(Carbon::now()) <= 7;
    }

    /**
     * Scope for user's favorites
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope for recent favorites
     */
    public function scopeRecent($query, $days = 30)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }
}
