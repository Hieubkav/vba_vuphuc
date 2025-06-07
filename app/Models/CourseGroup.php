<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Traits\ClearsViewCache;

class CourseGroup extends Model
{
    use HasFactory, ClearsViewCache;

    protected $fillable = [
        'name',
        'description',
        'group_link',
        'group_type',
        'max_members',
        'current_members',
        'order',
        'status',
    ];

    protected $casts = [
        'status' => 'string',
        'group_type' => 'string',
        'order' => 'integer',
        'max_members' => 'integer',
        'current_members' => 'integer',
    ];

    protected $attributes = [
        'current_members' => 0,
    ];



    // Relationships
    public function courses(): HasMany
    {
        return $this->hasMany(Course::class, 'course_group_id');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }



    public function scopeByType($query, $type)
    {
        return $query->where('group_type', $type);
    }

    // Accessors

    public function getFormattedGroupTypeAttribute()
    {
        return match($this->group_type) {
            'facebook' => 'Facebook',
            'zalo' => 'Zalo',
            'telegram' => 'Telegram',
            default => 'Facebook'
        };
    }

    public function getGroupTypeIconAttribute()
    {
        return match($this->group_type) {
            'facebook' => 'fab fa-facebook',
            'zalo' => 'fas fa-comments',
            'telegram' => 'fab fa-telegram',
            default => 'fas fa-users'
        };
    }

    // Helper methods
    public function isFull()
    {
        return $this->max_members && $this->current_members >= $this->max_members;
    }
}
