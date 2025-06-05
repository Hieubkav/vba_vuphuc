<?php

namespace App\Models;

use App\Traits\ClearsViewCache;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class Instructor extends Model
{
    use HasFactory, ClearsViewCache;

    protected $fillable = [
        'name',
        'slug',
        'email',
        'phone',
        'website',
        'bio',
        'avatar',
        'specialization',
        'experience_years',
        'education',
        'certifications',
        'social_links',
        'achievements',
        'teaching_philosophy',
        'hourly_rate',
        'status',
        'order',
    ];

    protected $casts = [
        'certifications' => 'array',
        'social_links' => 'array',
        'experience_years' => 'integer',
        'hourly_rate' => 'decimal:2',
        'status' => 'string',
        'order' => 'integer',
    ];

    // Relationships
    public function courses(): HasMany
    {
        return $this->hasMany(Course::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order')->orderBy('name');
    }

    // Accessors
    public function getFullBioAttribute(): string
    {
        $bio = $this->bio ?? '';

        if ($this->experience_years > 0) {
            $bio .= "\n\nKinh nghiệm: {$this->experience_years} năm";
        }

        if ($this->specialization) {
            $bio .= "\nChuyên môn: {$this->specialization}";
        }

        return trim($bio);
    }

    public function getCoursesCountAttribute(): int
    {
        return $this->courses()->count();
    }

    public function getActiveCoursesCountAttribute(): int
    {
        return $this->courses()->where('status', 'active')->count();
    }

    // Helper Methods
    public function generateSlug(): string
    {
        $baseSlug = \Illuminate\Support\Str::slug($this->name);
        $slug = $baseSlug;
        $counter = 1;

        while (static::where('slug', $slug)->where('id', '!=', $this->id)->exists()) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    public function hasAvatar(): bool
    {
        return $this->avatar && Storage::disk('public')->exists($this->avatar);
    }

    public function getAvatarUrlAttribute(): string
    {
        if ($this->hasAvatar()) {
            return asset('storage/' . $this->avatar);
        }
        return asset('images/default-avatar.png');
    }
}
