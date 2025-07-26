<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\ClearsViewCache;

class Slider extends Model
{
    use HasFactory, ClearsViewCache;

    protected $fillable = [
        'image_link',
        'title',
        'description',
        'link',
        'alt_text',
        'order',
        'status',
    ];

    protected $casts = [
        'order' => 'integer',
    ];
}
