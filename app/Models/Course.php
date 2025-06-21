<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use App\Traits\ClearsViewCache;

class Course extends Model
{
    use HasFactory, ClearsViewCache;

    protected $fillable = [
        'title',
        'description',
        'seo_title',
        'seo_description',
        'og_image_link',
        'slug',
        'thumbnail',
        'duration_hours',
        'level',
        'status',
        'is_featured',
        'order',
        'max_students',
        'start_date',
        'end_date',
        'requirements',
        'what_you_learn',
        'gg_form',
        'show_form_link',
        'show_group_link',
        'show_instructor',
        'cat_course_id',
        'instructor_id',
        'course_group_id',
    ];

    protected $casts = [
        'duration_hours' => 'integer',
        'is_featured' => 'boolean',
        'status' => 'string',
        'level' => 'string',
        'order' => 'integer',
        'max_students' => 'integer',
        'start_date' => 'date',
        'end_date' => 'date',
        'requirements' => 'array',
        'what_you_learn' => 'array',
        'show_form_link' => 'boolean',
        'show_group_link' => 'boolean',
        'show_instructor' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($course) {
            // Tự động set order nếu chưa có
            if (is_null($course->order)) {
                $course->order = 0;
            }

            // Tự động tạo slug nếu chưa có
            if (empty($course->slug) && !empty($course->title)) {
                $course->slug = $course->generateSlug();
            }
        });

        static::updating(function ($course) {
            // Tự động tạo slug nếu chưa có khi update
            if (empty($course->slug) && !empty($course->title)) {
                $course->slug = $course->generateSlug();
            }
        });
    }

    // Mutators để xử lý dữ liệu cũ
    public function getRequirementsAttribute($value)
    {
        if (is_string($value)) {
            // Nếu là string, cố gắng decode JSON
            $decoded = json_decode($value, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                return $decoded;
            }
            // Nếu không phải JSON, trả về array rỗng
            return [];
        }
        return $value;
    }

    public function getWhatYouLearnAttribute($value)
    {
        if (is_string($value)) {
            // Nếu là string, cố gắng decode JSON
            $decoded = json_decode($value, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                return $decoded;
            }
            // Nếu không phải JSON, trả về array rỗng
            return [];
        }
        return $value;
    }

    // Mutators để đảm bảo dữ liệu được lưu đúng định dạng
    public function setRequirementsAttribute($value)
    {
        if (is_array($value)) {
            $this->attributes['requirements'] = json_encode($value);
        } elseif (is_string($value)) {
            // Kiểm tra xem có phải JSON hợp lệ không
            $decoded = json_decode($value, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $this->attributes['requirements'] = $value;
            } else {
                $this->attributes['requirements'] = json_encode([]);
            }
        } else {
            $this->attributes['requirements'] = json_encode([]);
        }
    }

    public function setWhatYouLearnAttribute($value)
    {
        if (is_array($value)) {
            $this->attributes['what_you_learn'] = json_encode($value);
        } elseif (is_string($value)) {
            // Kiểm tra xem có phải JSON hợp lệ không
            $decoded = json_decode($value, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $this->attributes['what_you_learn'] = $value;
            } else {
                $this->attributes['what_you_learn'] = json_encode([]);
            }
        } else {
            $this->attributes['what_you_learn'] = json_encode([]);
        }
    }

    // Relationships
    public function images(): HasMany
    {
        return $this->hasMany(CourseImage::class);
    }

    public function materials(): HasMany
    {
        return $this->hasMany(CourseMaterial::class);
    }

    public function students(): BelongsToMany
    {
        return $this->belongsToMany(Student::class, 'course_student')
            ->withPivot([
                'enrolled_at',
                'completed_at',
                'status',
                'progress_percentage',
                'total_study_hours',
                'final_score',
                'notes',
                'completion_data'
            ])
            ->withTimestamps();
    }

    public function courseCategory(): BelongsTo
    {
        return $this->belongsTo(CatCourse::class, 'cat_course_id');
    }

    public function instructor(): BelongsTo
    {
        return $this->belongsTo(Instructor::class);
    }

    public function courseGroup(): BelongsTo
    {
        return $this->belongsTo(CourseGroup::class, 'course_group_id');
    }

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

    // Accessors & Mutators

    public function getEnrolledStudentsCountAttribute()
    {
        return $this->students()->wherePivot('status', '!=', 'dropped')->count();
    }

    public function getIsFullAttribute()
    {
        if (!$this->max_students) return false;
        return $this->enrolled_students_count >= $this->max_students;
    }

    // Helper Methods
    public function generateSlug(): string
    {
        $baseSlug = Str::slug($this->title);
        $slug = $baseSlug;
        $counter = 1;

        $query = static::where('slug', $slug);

        // Nếu đang update (có ID), loại trừ record hiện tại
        if ($this->exists) {
            $query->where('id', '!=', $this->id);
        }

        while ($query->exists()) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;

            $query = static::where('slug', $slug);
            if ($this->exists) {
                $query->where('id', '!=', $this->id);
            }
        }

        return $slug;
    }

    public function getMainImage()
    {
        $mainImage = $this->images()->where('is_main', true)->first()
            ?? $this->images()->where('status', 'active')->orderBy('order')->first();

        if ($mainImage && Storage::disk('public')->exists($mainImage->image_link)) {
            return asset('storage/' . $mainImage->image_link);
        }

        if ($this->thumbnail && Storage::disk('public')->exists($this->thumbnail)) {
            return asset('storage/' . $this->thumbnail);
        }

        return asset('images/placeholder-course.jpg');
    }

    public function canEnroll(): bool
    {
        return $this->status === 'active' && !$this->is_full;
    }

    public function getLevelDisplayAttribute()
    {
        $levels = [
            'beginner' => 'Cơ bản',
            'intermediate' => 'Trung cấp',
            'advanced' => 'Nâng cao'
        ];

        return $levels[$this->level] ?? 'Không xác định';
    }
}
