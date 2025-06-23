<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use App\Traits\ClearsViewCache;

class Post extends Model
{
    use HasFactory, ClearsViewCache;

    protected $fillable = [
        'title',
        'content',
        'content_builder',
        'excerpt',
        'reading_time',
        'seo_title',
        'seo_description',
        'og_image_link',
        'slug',
        'thumbnail',
        'order',
        'status',
        'is_featured',
        'published_at',
        'category_id',
    ];

    protected $casts = [
        'content_builder' => 'array',
        'reading_time' => 'integer',
        'is_featured' => 'boolean',
        'published_at' => 'datetime',
        'status' => 'string',
        'order' => 'integer',
    ];

    // Quan hệ với PostImage
    public function images()
    {
        return $this->hasMany(PostImage::class);
    }

    // Quan hệ với PostMedia
    public function media()
    {
        return $this->hasMany(PostMedia::class);
    }

    // Quan hệ với MenuItem
    public function menuItems()
    {
        return $this->hasMany(MenuItem::class);
    }

    // Quan hệ với CatPost (category chính) - giữ lại để tương thích ngược
    public function category()
    {
        return $this->belongsTo(CatPost::class, 'category_id');
    }

    // Quan hệ với CatPost (many-to-many) - quan hệ chính
    public function categories()
    {
        return $this->belongsToMany(CatPost::class, 'post_categories', 'post_id', 'cat_post_id');
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

    public function scopePublished($query)
    {
        return $query->where('status', 'active')
                    ->whereNotNull('published_at')
                    ->where('published_at', '<=', now());
    }

    public function scopeWithMedia($query)
    {
        return $query->with(['images' => function($q) {
            $q->where('status', 'active')->orderBy('order');
        }, 'media' => function($q) {
            $q->where('status', 'active')->orderBy('order');
        }]);
    }

    // Accessors & Methods
    public function getReadingTime()
    {
        if ($this->reading_time) {
            return $this->reading_time;
        }

        // Auto-calculate from content
        $content = $this->content ?? '';
        if ($this->content_builder && is_array($this->content_builder)) {
            $builderContent = '';
            foreach ($this->content_builder as $block) {
                if (isset($block['data']['content'])) {
                    $builderContent .= ' ' . strip_tags($block['data']['content']);
                }
            }
            $content .= ' ' . $builderContent;
        }

        $wordCount = str_word_count(strip_tags($content));
        return max(1, ceil($wordCount / 200)); // 200 words per minute
    }

    public function getExcerpt($length = 160)
    {
        if ($this->excerpt) {
            return $this->excerpt;
        }

        // Auto-generate from content
        $content = $this->content ?? '';
        if ($this->content_builder && is_array($this->content_builder)) {
            foreach ($this->content_builder as $block) {
                if (isset($block['data']['content']) && $block['type'] === 'paragraph') {
                    $content = strip_tags($block['data']['content']);
                    break;
                }
            }
        }

        $plainText = strip_tags($content);
        $plainText = preg_replace('/\s+/', ' ', trim($plainText));

        if (strlen($plainText) <= $length) {
            return $plainText;
        }

        $truncated = substr($plainText, 0, $length - 3);
        $lastSpace = strrpos($truncated, ' ');

        if ($lastSpace !== false) {
            $truncated = substr($truncated, 0, $lastSpace);
        }

        return $truncated . '...';
    }

    public function getFeaturedImage()
    {
        // Priority: thumbnail -> main image from images -> first gallery image
        if ($this->thumbnail && Storage::disk('public')->exists($this->thumbnail)) {
            return asset('storage/' . $this->thumbnail);
        }

        $mainImage = $this->images()->where('image_type', 'featured')->where('status', 'active')->first()
            ?? $this->images()->where('status', 'active')->orderBy('order')->first();

        if ($mainImage && Storage::disk('public')->exists($mainImage->image_link)) {
            return asset('storage/' . $mainImage->image_link);
        }

        return asset('images/placeholder-post.jpg');
    }

    public function hasRichContent(): bool
    {
        return !empty($this->content_builder) && is_array($this->content_builder) && count($this->content_builder) > 0;
    }

    public function getContentBlocks()
    {
        return $this->content_builder ?? [];
    }



    public function isPublished(): bool
    {
        return $this->status === 'active'
            && $this->published_at
            && $this->published_at <= now();
    }
}
