<?php

namespace App\Models;

use App\Traits\ClearsViewCache;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    use HasFactory, ClearsViewCache;

    protected $fillable = [
        'name',
        'email',
        'location',
        'content',
        'edited_content',
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
     * Scope for pending testimonials (feedback chưa được duyệt)
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope for ordering
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('order')->orderBy('created_at', 'desc');
    }

    /**
     * Lấy nội dung hiển thị (ưu tiên edited_content nếu có)
     */
    public function getDisplayContentAttribute(): string
    {
        return $this->edited_content ?: $this->content;
    }

    /**
     * Kiểm tra xem testimonial có nội dung đã được biên tập không
     */
    public function hasEditedContent(): bool
    {
        return !empty($this->edited_content);
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
