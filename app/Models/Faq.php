<?php

namespace App\Models;

use App\Traits\ClearsViewCache;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
    use HasFactory, ClearsViewCache;

    protected $fillable = [
        'question',
        'answer',
        'category',
        'order',
        'status',
    ];

    protected $casts = [
        'order' => 'integer',
        'status' => 'string',
    ];

    /**
     * Scope for active FAQs
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
     * Scope by category
     */
    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    /**
     * Get available categories
     */
    public static function getCategories()
    {
        return [
            'general' => 'Câu hỏi chung',
            'products' => 'Sản phẩm & Dịch vụ',
            'courses' => 'Khóa học',
            'support' => 'Hỗ trợ kỹ thuật',
            'partnership' => 'Hợp tác đại lý',
            'shipping' => 'Giao hàng & Thanh toán',
        ];
    }
}
