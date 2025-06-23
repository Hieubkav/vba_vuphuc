<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class PostMedia extends Model
{
    use HasFactory;

    protected $fillable = [
        'post_id',
        'media_type',
        'file_path',
        'file_name',
        'file_size',
        'mime_type',
        'title',
        'description',
        'alt_text',
        'thumbnail_path',
        'duration',
        'metadata',
        'embed_code',
        'embed_url',
        'order',
        'status',
    ];

    protected $casts = [
        'metadata' => 'array',
        'file_size' => 'integer',
        'duration' => 'integer',
        'order' => 'integer',
        'status' => 'string',
        'media_type' => 'string',
    ];

    // Relationships
    public function post(): BelongsTo
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
        return $query->where('media_type', $type);
    }

    public function scopeVideos($query)
    {
        return $query->where('media_type', 'video');
    }

    public function scopeAudios($query)
    {
        return $query->where('media_type', 'audio');
    }

    public function scopeDocuments($query)
    {
        return $query->where('media_type', 'document');
    }

    public function scopeEmbeds($query)
    {
        return $query->where('media_type', 'embed');
    }

    public function scopeDownloads($query)
    {
        return $query->where('media_type', 'download');
    }

    // Accessors & Methods
    public function getFileUrl()
    {
        if ($this->file_path && Storage::disk('public')->exists($this->file_path)) {
            return asset('storage/' . $this->file_path);
        }
        return null;
    }

    public function getThumbnailUrl()
    {
        if ($this->thumbnail_path && Storage::disk('public')->exists($this->thumbnail_path)) {
            return asset('storage/' . $this->thumbnail_path);
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

    public function getFormattedDuration()
    {
        if (!$this->duration) return null;
        
        $minutes = floor($this->duration / 60);
        $seconds = $this->duration % 60;
        
        return sprintf('%02d:%02d', $minutes, $seconds);
    }

    public function isVideo(): bool
    {
        return $this->media_type === 'video';
    }

    public function isAudio(): bool
    {
        return $this->media_type === 'audio';
    }

    public function isDocument(): bool
    {
        return $this->media_type === 'document';
    }

    public function isEmbed(): bool
    {
        return $this->media_type === 'embed';
    }

    public function isDownload(): bool
    {
        return $this->media_type === 'download';
    }

    public function getEmbedHtml()
    {
        if (!$this->isEmbed() || !$this->embed_code) {
            return null;
        }
        
        return $this->embed_code;
    }

    public function getYouTubeId()
    {
        if (!$this->embed_url) return null;
        
        preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/)([^&\n?#]+)/', $this->embed_url, $matches);
        return $matches[1] ?? null;
    }

    public function getVimeoId()
    {
        if (!$this->embed_url) return null;
        
        preg_match('/vimeo\.com\/(\d+)/', $this->embed_url, $matches);
        return $matches[1] ?? null;
    }
}
