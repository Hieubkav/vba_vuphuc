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
        'description',
        'order',
        'status',
    ];

    protected $casts = [
        'status' => 'string',
        'order' => 'integer',
    ];

    // Quan hệ với Post (one-to-many) - giữ lại để tương thích ngược
    public function posts()
    {
        return $this->hasMany(Post::class, 'category_id');
    }

    // Quan hệ với Post (many-to-many) - quan hệ chính
    public function postsMany()
    {
        return $this->belongsToMany(Post::class, 'post_categories', 'cat_post_id', 'post_id');
    }

    // Quan hệ với Course đã bị xóa - Course giờ sử dụng CatCourse
    // public function courses()
    // {
    //     return $this->hasMany(Course::class, 'category_id');
    // }



    // Quan hệ với MenuItem
    public function menuItems()
    {
        return $this->hasMany(MenuItem::class, 'cat_post_id');
    }

    // Helper methods
    public function getPostsCount()
    {
        // Sử dụng quan hệ many-to-many mới
        return $this->postsMany()->where('status', 'active')->count();
    }

    public function getCoursesCount()
    {
        // Courses đã chuyển sang sử dụng CatCourse
        return 0;
    }

    public function getTotalItemsCount()
    {
        return $this->getPostsCount();
    }
}
