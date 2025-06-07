<?php

namespace App\Observers;

use App\Models\CatCourse;
use Illuminate\Support\Str;

class CatCourseObserver
{
    /**
     * Handle the CatCourse "creating" event.
     */
    public function creating(CatCourse $catCourse): void
    {
        $this->generateSeoData($catCourse);
    }

    /**
     * Handle the CatCourse "updating" event.
     */
    public function updating(CatCourse $catCourse): void
    {
        $this->generateSeoData($catCourse);
    }

    /**
     * Tự động sinh dữ liệu SEO nếu để trống
     */
    private function generateSeoData(CatCourse $catCourse): void
    {
        // Tự động sinh slug nếu để trống
        if (empty($catCourse->slug) && !empty($catCourse->name)) {
            $catCourse->slug = Str::slug($catCourse->name);
        }

        // Tự động sinh SEO title nếu để trống
        if (empty($catCourse->seo_title) && !empty($catCourse->name)) {
            $catCourse->seo_title = $catCourse->name . ' - Khóa học VBA Vũ Phúc';
        }

        // Tự động sinh SEO description nếu để trống
        if (empty($catCourse->seo_description)) {
            if (!empty($catCourse->description)) {
                // Sử dụng mô tả danh mục nếu có
                $catCourse->seo_description = Str::limit(strip_tags($catCourse->description), 150);
            } elseif (!empty($catCourse->name)) {
                // Tạo mô tả mặc định từ tên danh mục
                $catCourse->seo_description = "Khám phá các khóa học {$catCourse->name} chất lượng cao tại VBA Vũ Phúc. Học từ cơ bản đến nâng cao với giảng viên giàu kinh nghiệm.";
            }
        }

        // Tự động sử dụng image làm og_image nếu og_image trống
        if (empty($catCourse->og_image_link) && !empty($catCourse->image)) {
            $catCourse->og_image_link = $catCourse->image;
        }
    }
}
