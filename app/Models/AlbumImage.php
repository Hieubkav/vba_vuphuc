<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Traits\ClearsViewCache;

class AlbumImage extends Model
{
    use HasFactory, ClearsViewCache;

    protected $fillable = [
        'album_id',
        'image_path',
        'alt_text',
        'caption',
        'order',
        'status',
        'is_featured',
        'file_size',
        'width',
        'height',
    ];

    protected $casts = [
        'album_id' => 'integer',
        'order' => 'integer',
        'status' => 'string',
        'is_featured' => 'boolean',
        'file_size' => 'integer',
        'width' => 'integer',
        'height' => 'integer',
    ];

    // Relationships
    public function album(): BelongsTo
    {
        return $this->belongsTo(Album::class);
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

    public function scopeOrdered($query)
    {
        return $query->orderBy('order')->orderBy('created_at');
    }

    // Accessors
    public function getImageUrlAttribute()
    {
        return $this->image_path ? asset('storage/' . $this->image_path) : null;
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

    public function getDimensionsAttribute()
    {
        if ($this->width && $this->height) {
            return $this->width . ' x ' . $this->height;
        }
        return null;
    }
}
