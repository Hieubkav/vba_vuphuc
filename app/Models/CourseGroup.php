<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\ClearsViewCache;

class CourseGroup extends Model
{
    use HasFactory, ClearsViewCache;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'thumbnail',
        'group_link',
        'group_type',
        'level',
        'max_members',
        'current_members',
        'instructor_name',
        'instructor_bio',
        'color',
        'icon',
        'is_featured',
        'order',
        'status',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'status' => 'string',
        'level' => 'string',
        'group_type' => 'string',
        'order' => 'integer',
        'max_members' => 'integer',
        'current_members' => 'integer',
    ];

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeByLevel($query, $level)
    {
        return $query->where('level', $level);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('group_type', $type);
    }

    // Accessors
    public function getThumbnailUrlAttribute()
    {
        if ($this->thumbnail && file_exists(public_path('storage/' . $this->thumbnail))) {
            return asset('storage/' . $this->thumbnail);
        }
        return asset('images/default-course-group.jpg');
    }

    public function getFormattedLevelAttribute()
    {
        return match($this->level) {
            'beginner' => 'Cơ bản',
            'intermediate' => 'Trung cấp',
            'advanced' => 'Nâng cao',
            default => 'Cơ bản'
        };
    }

    public function getFormattedGroupTypeAttribute()
    {
        return match($this->group_type) {
            'facebook' => 'Facebook',
            'zalo' => 'Zalo',
            'telegram' => 'Telegram',
            default => 'Facebook'
        };
    }

    public function getLevelColorAttribute()
    {
        return match($this->level) {
            'beginner' => 'bg-green-100 text-green-800',
            'intermediate' => 'bg-yellow-100 text-yellow-800',
            'advanced' => 'bg-red-100 text-red-800',
            default => 'bg-green-100 text-green-800'
        };
    }

    public function getGroupTypeIconAttribute()
    {
        // Ưu tiên icon từ database, fallback theo group_type
        if ($this->icon) {
            return $this->icon;
        }

        return match($this->group_type) {
            'facebook' => 'fab fa-facebook',
            'zalo' => 'fas fa-comments',
            'telegram' => 'fab fa-telegram',
            default => 'fas fa-users'
        };
    }

    // Helper methods
    public function hasThumbnail()
    {
        return $this->thumbnail && file_exists(public_path('storage/' . $this->thumbnail));
    }

    public function isFull()
    {
        return $this->max_members && $this->current_members >= $this->max_members;
    }

    public function getAvailableSlots()
    {
        if (!$this->max_members) return null;
        return $this->max_members - $this->current_members;
    }
}
