<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Carbon;

class Student extends Authenticatable
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'birth_date',
        'gender',
        'address',
        'occupation',
        'education_level',
        'learning_goals',
        'interests',
        'avatar',
        'email_verified_at',
        'status',
        'order',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'interests' => 'array',
        'email_verified_at' => 'timestamp',
        'status' => 'string',
        'order' => 'integer',
    ];

    // Relationships
    public function courses(): BelongsToMany
    {
        return $this->belongsToMany(Course::class, 'course_student')
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

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeByEducationLevel($query, $level)
    {
        return $query->where('education_level', $level);
    }

    // Accessors
    public function getAgeAttribute()
    {
        return $this->birth_date ? Carbon::parse($this->birth_date)->age : null;
    }

    public function getGenderDisplayAttribute()
    {
        $genders = [
            'male' => 'Nam',
            'female' => 'Nữ',
            'other' => 'Khác'
        ];

        return $genders[$this->gender] ?? 'Không xác định';
    }

    public function getEducationLevelDisplayAttribute()
    {
        $levels = [
            'high_school' => 'Trung học phổ thông',
            'college' => 'Cao đẳng',
            'university' => 'Đại học',
            'master' => 'Thạc sĩ',
            'phd' => 'Tiến sĩ',
            'other' => 'Khác'
        ];

        return $levels[$this->education_level] ?? 'Không xác định';
    }

    public function getAvatarUrlAttribute()
    {
        if (!$this->avatar) {
            return asset('images/default-avatar.png');
        }

        if (str_starts_with($this->avatar, 'http')) {
            return $this->avatar;
        }

        return asset('storage/' . $this->avatar);
    }

    // Helper Methods
    public function getEnrolledCoursesCount()
    {
        return $this->courses()->wherePivot('status', '!=', 'dropped')->count();
    }

    public function getCompletedCoursesCount()
    {
        return $this->courses()->wherePivot('status', 'completed')->count();
    }

    public function getAverageProgress()
    {
        $courses = $this->courses()->wherePivot('status', '!=', 'dropped')->get();
        if ($courses->isEmpty()) return 0;

        $totalProgress = $courses->sum('pivot.progress_percentage');
        return round($totalProgress / $courses->count(), 2);
    }

    public function isEnrolledIn($courseId): bool
    {
        return $this->courses()
            ->where('course_id', $courseId)
            ->wherePivot('status', '!=', 'dropped')
            ->exists();
    }
}
