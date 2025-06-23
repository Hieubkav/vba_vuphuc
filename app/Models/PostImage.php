<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class PostImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'post_id',
        'image_link',
        'image_type',
        'alt_text',
        'caption',
        'width',
        'height',
        'file_size',
        'mime_type',
        'title',
        'description',
        'order',
        'status',
    ];

    protected $casts = [
        'width' => 'integer',
        'height' => 'integer',
        'file_size' => 'integer',
        'order' => 'integer',
        'status' => 'string',
        'image_type' => 'string',
    ];

    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeByType($query, $type)
    {
        return $query->where('image_type', $type);
    }

    public function scopeGallery($query)
    {
        return $query->where('image_type', 'gallery');
    }

    public function scopeFeatured($query)
    {
        return $query->where('image_type', 'featured');
    }

    public function scopeInline($query)
    {
        return $query->where('image_type', 'inline');
    }

    public function scopeThumbnail($query)
    {
        return $query->where('image_type', 'thumbnail');
    }

    // Methods
    public function getImageUrl()
    {
        if ($this->image_link && Storage::disk('public')->exists($this->image_link)) {
            return asset('storage/' . $this->image_link);
        }
        return null;
    }

    public function getFormattedFileSize()
    {
        if (!$this->file_size) return null;

        $bytes = $this->file_size;
        $units = ['B', 'KB', 'MB', 'GB'];

        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, 2) . ' ' . $units[$i];
    }

    public function getDimensions()
    {
        if ($this->width && $this->height) {
            return $this->width . ' × ' . $this->height . ' px';
        }
        return null;
    }

    public function getAspectRatio()
    {
        if ($this->width && $this->height) {
            $gcd = $this->gcd($this->width, $this->height);
            return ($this->width / $gcd) . ':' . ($this->height / $gcd);
        }
        return null;
    }

    private function gcd($a, $b)
    {
        return $b ? $this->gcd($b, $a % $b) : $a;
    }

    public function isLandscape(): bool
    {
        return $this->width && $this->height && $this->width > $this->height;
    }

    public function isPortrait(): bool
    {
        return $this->width && $this->height && $this->height > $this->width;
    }

    public function isSquare(): bool
    {
        return $this->width && $this->height && $this->width === $this->height;
    }
}
