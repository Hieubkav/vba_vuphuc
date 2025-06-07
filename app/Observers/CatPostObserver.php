<?php

namespace App\Observers;

use App\Models\CatPost;
use App\Providers\ViewServiceProvider;
use Illuminate\Support\Str;

class CatPostObserver
{
    /**
     * Handle the CatPost "creating" event.
     */
    public function creating(CatPost $catPost): void
    {
        $this->generateSeoFields($catPost);
        $this->generateSlug($catPost);
    }

    /**
     * Handle the CatPost "updating" event.
     */
    public function updating(CatPost $catPost): void
    {
        $this->generateSeoFields($catPost);
        $this->generateSlug($catPost);
    }

    /**
     * Handle the CatPost "created" event.
     */
    public function created(CatPost $catPost): void
    {
        ViewServiceProvider::refreshCache('posts');
    }

    /**
     * Handle the CatPost "updated" event.
     */
    public function updated(CatPost $catPost): void
    {
        ViewServiceProvider::refreshCache('posts');
    }

    /**
     * Handle the CatPost "deleted" event.
     */
    public function deleted(CatPost $catPost): void
    {
        ViewServiceProvider::refreshCache('posts');
    }

    /**
     * Tự động sinh SEO fields nếu để trống
     */
    private function generateSeoFields(CatPost $catPost): void
    {
        // Tự động sinh SEO title nếu để trống
        if (empty($catPost->seo_title)) {
            $catPost->seo_title = $catPost->name . ' - Danh mục bài viết';
        }

        // Tự động sinh SEO description nếu để trống
        if (empty($catPost->seo_description)) {
            if (!empty($catPost->description)) {
                $catPost->seo_description = Str::limit(strip_tags($catPost->description), 160);
            } else {
                $catPost->seo_description = 'Khám phá các bài viết trong danh mục ' . $catPost->name . ' tại VBA Vũ Phúc.';
            }
        }

        // Tự động sinh OG image nếu để trống - sử dụng ảnh mặc định
        if (empty($catPost->og_image_link)) {
            $catPost->og_image_link = asset('images/default-category-og.jpg');
        }
    }

    /**
     * Tự động sinh slug nếu để trống
     */
    private function generateSlug(CatPost $catPost): void
    {
        if (empty($catPost->slug)) {
            $baseSlug = Str::slug($catPost->name);
            $slug = $baseSlug;
            $counter = 1;

            // Kiểm tra slug trùng lặp
            while (CatPost::where('slug', $slug)->where('id', '!=', $catPost->id ?? 0)->exists()) {
                $slug = $baseSlug . '-' . $counter;
                $counter++;
            }

            $catPost->slug = $slug;
        }
    }
}
