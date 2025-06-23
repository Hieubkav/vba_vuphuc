<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\ClearsViewCache;

class MenuItem extends Model
{
    use HasFactory, ClearsViewCache;

    protected $fillable = [
        'parent_id',
        'label',
        'type',
        'link',
        'cat_post_id',
        'post_id',
        'cat_course_id',
        'course_id',
        'course_group_id',
        'order',
        'status',
    ];

    protected $casts = [
        'type' => 'string',
        'status' => 'string',
        'order' => 'integer',
    ];

    // Quan hệ parent-child
    public function parent()
    {
        return $this->belongsTo(MenuItem::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(MenuItem::class, 'parent_id');
    }

    // Quan hệ với các model khác
    public function category()
    {
        return $this->belongsTo(CatPost::class, 'cat_post_id');
    }

    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function catCourse()
    {
        return $this->belongsTo(CatCourse::class, 'cat_course_id');
    }

    public function courseGroup()
    {
        return $this->belongsTo(CourseGroup::class, 'course_group_id');
    }

    // Helper method để lấy URL - Cập nhật cho website khóa học
    public function getUrl()
    {
        switch ($this->type) {
            case 'link':
                return $this->link;
            case 'cat_post':
                return $this->category ? route('posts.category', $this->category->slug) : '#';
            case 'all_posts':
                return route('posts.categories');
            case 'post':
                return $this->post ? route('posts.show', $this->post->slug) : '#';
            case 'cat_course':
                return $this->catCourse ? route('courses.category', $this->catCourse->slug) : '#';
            case 'all_courses':
                return route('courses.index');
            case 'course':
                return $this->course ? route('courses.show', $this->course->slug) : '#';
            case 'course_group':
                return $this->courseGroup ? $this->courseGroup->group_link : '#';
            case 'display_only':
                return 'javascript:void(0)'; // Không dẫn đến đâu cả
            default:
                return '#';
        }
    }

    // Helper method để lấy icon cho menu
    public function getIcon()
    {
        return match($this->type) {
            'cat_course' => 'fas fa-folder-open',
            'all_courses' => 'fas fa-book-open',
            'course' => 'fas fa-graduation-cap',
            'course_group' => 'fas fa-users',
            'cat_post' => 'fas fa-folder',
            'all_posts' => 'fas fa-newspaper',
            'post' => 'fas fa-file-alt',
            'link' => 'fas fa-external-link-alt',
            default => 'fas fa-link'
        };
    }
}
