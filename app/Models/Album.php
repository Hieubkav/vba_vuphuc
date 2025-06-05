<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
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
        'total_pages' => 'integer',
        'file_size' => 'integer',
        'download_count' => 'integer',
        'view_count' => 'integer',
    ];

    // Relationships
    public function images(): HasMany
    {
        return $this->hasMany(AlbumImage::class);
    }

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
}
