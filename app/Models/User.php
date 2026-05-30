<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Relationship with user_favorites - Encapsulation
     */
    public function favorites()
    {
        return $this->hasMany(user_favorites::class, 'user_id');
    }

    /**
     * Relationship with ulasan (reviews) - Encapsulation
     */
    public function reviews()
    {
        return $this->hasMany(ulasan::class, 'user_id');
    }

    /**
     * Get user's favorite tempat makan
     */
    public function favoriteTempatMakan()
    {
        return $this->belongsToMany(
            tempat_makan::class, 
            'user_favorites', 
            'user_id', 
            'tempat_makan_id'
        )->withPivot('catatan', 'created_at');
    }

    /**
     * Check if user is admin
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user is regular user
     */
    public function isUser(): bool
    {
        return $this->role === 'user';
    }

    /**
     * Get total reviews count
     */
    public function getTotalReviewsCount(): int
    {
        return $this->reviews()->count();
    }

    /**
     * Get average rating given by user
     */
    public function getAverageRatingGiven(): float
    {
        return round($this->reviews()->avg('rating') ?? 0, 1);
    }
}
