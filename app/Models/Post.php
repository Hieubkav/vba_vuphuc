<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\ClearsViewCache;

class Post extends Model
{
    use HasFactory, ClearsViewCache;

    protected $fillable = [
        'title',
        'content',
        'seo_title',
        'seo_description',
        'og_image_link',
        'slug',
        'thumbnail',
        'is_featured',
        'type',
        'order',
        'status',
        'category_id',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'status' => 'string',
        'type' => 'string',
        'order' => 'integer',
    ];

    // Quan hệ với PostImage
    public function images()
    {
        return $this->hasMany(PostImage::class);
    }

    // Quan hệ với MenuItem
    public function menuItems()
    {
        return $this->hasMany(MenuItem::class);
    }

    // Quan hệ với CatPost (category chính)
    public function category()
    {
        return $this->belongsTo(CatPost::class, 'category_id');
    }
}
