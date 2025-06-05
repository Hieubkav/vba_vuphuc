<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Traits\ClearsViewCache;

class CatCourse extends Model
{
    use HasFactory, ClearsViewCache;

    protected $table = 'cat_courses';

    protected $fillable = [
        'name',
        'slug',
        'seo_title',
        'seo_description',
        'og_image_link',
        'image',
        'description',
        'color',
        'icon',
        'parent_id',
        'order',
        'status',
    ];

    protected $casts = [
        'status' => 'string',
        'order' => 'integer',
    ];

    // Quan hệ với Course (one-to-many)
    public function courses(): HasMany
    {
        return $this->hasMany(Course::class, 'cat_course_id');
    }

    // Quan hệ parent-child
    public function parent(): BelongsTo
    {
        return $this->belongsTo(CatCourse::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(CatCourse::class, 'parent_id');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeParent($query)
    {
        return $query->whereNull('parent_id');
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order')->orderBy('name');
    }

    // Helper methods
    public function getCoursesCount()
    {
        return $this->courses()->where('status', 'active')->count();
    }

    public function getActiveCoursesCount()
    {
        return $this->courses()->where('status', 'active')->count();
    }

    // Accessor cho URL hình ảnh
    public function getImageUrlAttribute()
    {
        if (!$this->image) {
            return null;
        }

        if (str_starts_with($this->image, 'http')) {
            return $this->image;
        }

        return asset('storage/' . $this->image);
    }

    // Accessor cho màu sắc mặc định
    public function getDisplayColorAttribute()
    {
        return $this->color ?: '#dc2626';
    }
}
