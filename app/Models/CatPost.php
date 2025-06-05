<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CatPost extends Model
{
    use HasFactory;

    protected $table = 'cat_posts';

    protected $fillable = [
        'name',
        'slug',
        'seo_title',
        'seo_description',
        'og_image_link',
        'image',
        'description',
        'parent_id',
        'order',
        'status',
    ];

    protected $casts = [
        'status' => 'string',
        'order' => 'integer',
    ];

    // Quan hệ với Post (one-to-many)
    public function posts()
    {
        return $this->hasMany(Post::class, 'category_id');
    }

    // Quan hệ với Course (one-to-many)
    public function courses()
    {
        return $this->hasMany(Course::class, 'category_id');
    }

    // Quan hệ parent-child
    public function parent()
    {
        return $this->belongsTo(CatPost::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(CatPost::class, 'parent_id');
    }

    // Quan hệ với MenuItem
    public function menuItems()
    {
        return $this->hasMany(MenuItem::class, 'cat_post_id');
    }

    // Helper methods
    public function getPostsCount()
    {
        return $this->posts()->where('status', 'active')->count();
    }

    public function getCoursesCount()
    {
        return $this->courses()->where('status', 'active')->count();
    }

    public function getTotalItemsCount()
    {
        return $this->getPostsCount() + $this->getCoursesCount();
    }
}
