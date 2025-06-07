<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HandlesFileObserver;

class Partner extends Model
{
    use HasFactory, HandlesFileObserver;

    protected $fillable = [
        'name',
        'logo_link',
        'website_link',
        'description',
        'order',
        'status',
    ];

    protected $casts = [
        'order' => 'integer',
    ];

    /**
     * Scope for active partners
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
        return $query->orderBy('order')->orderBy('name');
    }

    /**
     * Get logo URL
     */
    public function getLogoUrlAttribute()
    {
        if (empty($this->logo_link)) {
            return asset('images/placeholder.webp');
        }

        if (filter_var($this->logo_link, FILTER_VALIDATE_URL)) {
            return $this->logo_link;
        }

        return asset('storage/' . ltrim($this->logo_link, '/'));
    }

    /**
     * Get website URL with protocol
     */
    public function getWebsiteUrlAttribute()
    {
        if (empty($this->website_link)) {
            return null;
        }

        if (str_starts_with($this->website_link, 'http://') || str_starts_with($this->website_link, 'https://')) {
            return $this->website_link;
        }

        return 'https://' . $this->website_link;
    }

    /**
     * Files to be handled by observer
     */
    protected function getObserverFiles(): array
    {
        return [
            'logo_link'
        ];
    }
}
