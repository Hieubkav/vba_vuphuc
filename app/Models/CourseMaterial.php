<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class CourseMaterial extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'title',
        'description',
        'file_path',
        'file_name',
        'file_type',
        'file_size',
        'material_type',
        'access_type',
        'is_preview',
        'order',
    ];

    protected $casts = [
        'file_size' => 'integer',
        'is_preview' => 'boolean',
        'order' => 'integer',
        'access_type' => 'string',
    ];

    // Relationships
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    // Scopes

    public function scopePreview($query)
    {
        return $query->where('is_preview', true);
    }



    public function scopeByType($query, $type)
    {
        return $query->where('material_type', $type);
    }

    public function scopePublic($query)
    {
        return $query->where('access_type', 'public');
    }

    public function scopeEnrolledOnly($query)
    {
        return $query->where('access_type', 'enrolled');
    }

    public function scopeByAccessType($query, $accessType)
    {
        return $query->where('access_type', $accessType);
    }

    // Accessors
    public function getFormattedFileSizeAttribute()
    {
        $bytes = $this->file_size;

        if ($bytes >= 1073741824) {
            return number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 2) . ' KB';
        } else {
            return $bytes . ' bytes';
        }
    }

    // Alias for compatibility
    public function getFileSizeFormattedAttribute()
    {
        return $this->getFormattedFileSizeAttribute();
    }

    public function getFileUrlAttribute()
    {
        return Storage::url($this->file_path);
    }

    public function getDownloadUrlAttribute()
    {
        return route('course.material.download', $this->id);
    }

    public function getMaterialTypeDisplayAttribute()
    {
        $types = [
            'document' => 'Tài liệu',
            'video' => 'Video',
            'audio' => 'Audio',
            'image' => 'Hình ảnh',
            'other' => 'Khác'
        ];

        return $types[$this->material_type] ?? 'Không xác định';
    }

    public function getAccessTypeDisplayAttribute()
    {
        $types = [
            'public' => 'Mở',
            'enrolled' => 'Dành cho học viên'
        ];

        return $types[$this->access_type] ?? 'Không xác định';
    }

    public function getAccessTypeBadgeColorAttribute()
    {
        $colors = [
            'public' => 'bg-green-100 text-green-800',
            'enrolled' => 'bg-blue-100 text-blue-800'
        ];

        return $colors[$this->access_type] ?? 'bg-gray-100 text-gray-800';
    }

    // Helper methods cho trạng thái mở/đóng đơn giản
    public function isOpen()
    {
        return $this->access_type === 'public';
    }

    public function isClosed()
    {
        return $this->access_type === 'enrolled';
    }

    public function getStatusDisplayAttribute()
    {
        return $this->isOpen() ? 'Mở' : 'Dành cho học viên';
    }

    public function getStatusBadgeColorAttribute()
    {
        return $this->isOpen() ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800';
    }

    // Kiểm tra có thể xem và tải không (chỉ áp dụng cho tài liệu mở)
    public function canViewAndDownload()
    {
        return $this->isOpen(); // Tài liệu mở luôn có thể xem và tải
    }

    // Kiểm tra có thể xem và tải không với điều kiện đăng ký (cho tài liệu dành cho học viên)
    public function canViewAndDownloadIfEnrolled($user = null, $course = null)
    {
        if ($this->isOpen()) {
            return true; // Tài liệu mở luôn có thể xem và tải
        }

        if ($this->isClosed()) {
            if (!$user) {
                return false;
            }
            $course = $course ?? $this->course;
            return $course->students()->where('student_id', $user->id)->exists();
        }

        return false;
    }

    // Legacy methods for backward compatibility
    public function isPublic()
    {
        return $this->access_type === 'public';
    }

    public function isEnrolledOnly()
    {
        return $this->access_type === 'enrolled';
    }

    public function canAccess($user = null, $course = null)
    {
        // Tài liệu mở: ai cũng có thể truy cập
        if ($this->isPublic()) {
            return true;
        }

        // Tài liệu dành cho học viên: chỉ học viên đăng ký mới được truy cập
        if ($this->isEnrolledOnly()) {
            if (!$user) {
                return false;
            }

            $course = $course ?? $this->course;
            return $course->students()->where('student_id', $user->id)->exists();
        }

        return false;
    }

    public function getFileIconAttribute()
    {
        $icons = [
            'pdf' => 'fas fa-file-pdf text-red-500',
            'doc' => 'fas fa-file-word text-blue-500',
            'docx' => 'fas fa-file-word text-blue-500',
            'ppt' => 'fas fa-file-powerpoint text-orange-500',
            'pptx' => 'fas fa-file-powerpoint text-orange-500',
            'xls' => 'fas fa-file-excel text-green-500',
            'xlsx' => 'fas fa-file-excel text-green-500',
            'mp4' => 'fas fa-file-video text-purple-500',
            'mp3' => 'fas fa-file-audio text-yellow-500',
            'jpg' => 'fas fa-file-image text-pink-500',
            'jpeg' => 'fas fa-file-image text-pink-500',
            'png' => 'fas fa-file-image text-pink-500',
            'zip' => 'fas fa-file-archive text-gray-500',
            'rar' => 'fas fa-file-archive text-gray-500',
        ];

        return $icons[strtolower($this->file_type)] ?? 'fas fa-file text-gray-400';
    }

    // Helper Methods
    public function canDownload(): bool
    {
        return $this->isOpen(); // Chỉ tài liệu mở mới có thể tải
    }

    public function canPreview(): bool
    {
        return $this->is_preview;
    }

    public function exists(): bool
    {
        return Storage::exists($this->file_path);
    }

    public function delete()
    {
        // Xóa file vật lý trước khi xóa record
        if ($this->exists()) {
            Storage::delete($this->file_path);
        }

        return parent::delete();
    }
}
