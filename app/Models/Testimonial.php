<?php

namespace App\Models;

use App\Traits\ClearsViewCache;
use App\Traits\HandlesFileObserver;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    use HasFactory, ClearsViewCache, HandlesFileObserver;

    protected $fillable = [
        'name',
        'position',
        'company',
        'location',
        'content',
        'rating',
        'avatar',
        'order',
        'status',
    ];

    protected $casts = [
        'rating' => 'integer',
        'order' => 'integer',
        'status' => 'string',
    ];

    /**
     * Scope for active testimonials
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope for ordering
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('order')->orderBy('created_at', 'desc');
    }

    /**
     * Get avatar URL with fallback
     */
    public function getAvatarUrlAttribute()
    {
        if (empty($this->avatar)) {
            // Fallback to random avatar based on gender or name
            $gender = str_contains(strtolower($this->name), 'thị') ? 'women' : 'men';
            $randomId = abs(crc32($this->name)) % 100;
            return "https://randomuser.me/api/portraits/{$gender}/{$randomId}.jpg";
        }

        if (filter_var($this->avatar, FILTER_VALIDATE_URL)) {
            return $this->avatar;
        }

        return asset('storage/' . ltrim($this->avatar, '/'));
    }

    /**
     * Get rating stars as array
     */
    public function getRatingStarsAttribute()
    {
        $stars = [];
        for ($i = 1; $i <= 5; $i++) {
            $stars[] = $i <= $this->rating;
        }
        return $stars;
    }
}
