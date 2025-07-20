<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Traits\ClearsViewCache;

class Album extends Model
{
    use HasFactory, ClearsViewCache;

    protected $fillable = [
        'title',
        'description',
        'seo_title',
        'seo_description',
        'og_image_link',
        'slug',
        'pdf_file',
        'thumbnail',
        'published_date',
        'status',
        'order',
        'featured',
        'media_type',
        'total_pages',
        'file_size',
        'download_count',
        'view_count',
    ];

    protected $casts = [
        'published_date' => 'date',
        'status' => 'string',
        'order' => 'integer',
        'featured' => 'boolean',
        'media_type' => 'string',
        'total_pages' => 'integer',
        'file_size' => 'integer',
        'download_count' => 'integer',
        'view_count' => 'integer',
    ];

    // Relationships - Đã đơn giản hóa, không cần relationship với images

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeFeatured($query)
    {
        return $query->where('featured', true);
    }

    public function scopePublished($query)
    {
        return $query->where('published_date', '<=', now());
    }

    public function scopePdfType($query)
    {
        return $query->where('media_type', 'pdf');
    }

    public function scopeImagesType($query)
    {
        return $query->where('media_type', 'images');
    }

    // Accessors
    public function getPdfUrlAttribute()
    {
        return $this->pdf_file ? asset('storage/' . $this->pdf_file) : null;
    }

    public function getThumbnailUrlAttribute()
    {
        return $this->thumbnail ? asset('storage/' . $this->thumbnail) : null;
    }

    public function getFormattedFileSizeAttribute()
    {
        if (!$this->file_size) return null;

        $bytes = $this->file_size;
        $units = ['B', 'KB', 'MB', 'GB'];

        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, 2) . ' ' . $units[$i];
    }



    // Methods
    public function incrementViewCount()
    {
        $this->increment('view_count');
    }

    public function incrementDownloadCount()
    {
        $this->increment('download_count');
    }

    // Auto-generate SEO fields if empty
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($album) {
            // Auto-generate SEO title if empty
            if (empty($album->seo_title) && !empty($album->title)) {
                $album->seo_title = $album->title;
            }

            // Auto-generate SEO description if empty
            if (empty($album->seo_description) && !empty($album->description)) {
                $album->seo_description = \Illuminate\Support\Str::limit(strip_tags($album->description), 160);
            }

            // Auto-generate OG image from thumbnail if empty
            if (empty($album->og_image_link) && !empty($album->thumbnail)) {
                $album->og_image_link = $album->thumbnail;
            }

            // Auto-generate slug if empty
            if (empty($album->slug) && !empty($album->title)) {
                $album->slug = \Illuminate\Support\Str::slug($album->title);
            }
        });
    }
}
