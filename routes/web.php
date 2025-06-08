<?php

/*
|--------------------------------------------------------------------------
| Web Routes - VBA Vũ Phúc Learning Platform
|--------------------------------------------------------------------------
|
| Định nghĩa tất cả routes cho website khóa học VBA Vũ Phúc
| Bao gồm: Trang chủ, Khóa học, Học viên, Bài viết, Giảng viên, v.v.
|
*/

use App\Http\Controllers\AlbumController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\CourseGroupController;
use App\Http\Controllers\FaviconController;
use App\Http\Controllers\InstructorController;
use App\Http\Controllers\MainController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\StudentController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Trang chủ
|--------------------------------------------------------------------------
*/
Route::controller(MainController::class)->group(function () {
    Route::get('/', 'storeFront')->name('storeFront');
});

/*
|--------------------------------------------------------------------------
| Routes Khóa học
|--------------------------------------------------------------------------
| Quản lý tất cả routes liên quan đến khóa học:
| - Danh sách khóa học
| - Chi tiết khóa học  
| - Danh mục khóa học
| - Tìm kiếm khóa học
*/
Route::controller(CourseController::class)->group(function () {
    Route::get('/khoa-hoc', 'index')->name('courses.index');
    Route::get('/khoa-hoc/danh-muc/{slug}', 'category')->name('courses.category');
    Route::get('/khoa-hoc/chuyen-muc/{slug}', 'catCategory')->name('courses.cat-category');
    Route::get('/khoa-hoc/{slug}', 'show')->name('courses.show');
    Route::get('/api/courses/search', 'searchSuggestions')->name('courses.search');
});

/*
|--------------------------------------------------------------------------
| Routes Học viên
|--------------------------------------------------------------------------
| Quản lý đăng ký học viên và thông tin cá nhân:
| - Đăng ký học viên mới
| - Đăng ký khóa học
| - Profile học viên
*/
Route::controller(StudentController::class)->group(function () {
    Route::get('/dang-ky-hoc-vien', 'register')->name('students.register');
    Route::post('/dang-ky-hoc-vien', 'store')->name('students.store');
    Route::get('/dang-ky-thanh-cong', 'success')->name('students.success');
    Route::post('/api/enroll-course', 'enrollCourse')->name('students.enroll');
    Route::get('/hoc-vien/profile', 'profile')->name('students.profile');
});

/*
|--------------------------------------------------------------------------
| Routes Giảng viên
|--------------------------------------------------------------------------
| Hiển thị thông tin chi tiết giảng viên
*/
Route::controller(InstructorController::class)->group(function () {
    Route::get('/giang-vien/{slug}', 'show')->name('instructors.show');
});

/*
|--------------------------------------------------------------------------
| Routes Nhóm khóa học
|--------------------------------------------------------------------------
| Quản lý các nhóm học tập và cộng đồng
*/
Route::controller(CourseGroupController::class)->group(function () {
    Route::get('/nhom-hoc-tap', 'index')->name('course-groups.index');
    Route::get('/nhom-hoc-tap/{id}', 'show')->name('course-groups.show');
});

/*
|--------------------------------------------------------------------------
| Routes Album & Thư viện ảnh
|--------------------------------------------------------------------------
| API endpoints cho album ảnh khóa học:
| - Tăng lượt xem
| - Tăng lượt tải
| - Lấy danh sách ảnh
*/
Route::controller(AlbumController::class)->group(function () {
    Route::post('/api/albums/{album}/view', 'incrementView')->name('albums.view');
    Route::post('/api/albums/{album}/download', 'incrementDownload')->name('albums.download');
    Route::get('/api/albums/{album}/images', 'getImages')->name('albums.images');
});

/*
|--------------------------------------------------------------------------
| Routes Bài viết & Tin tức
|--------------------------------------------------------------------------
| Quản lý bài viết, tin tức và dịch vụ:
| - Danh sách bài viết
| - Chi tiết bài viết
| - Danh mục bài viết
*/
Route::controller(PostController::class)->group(function () {
    Route::get('/bai-viet', 'index')->name('posts.index');
    Route::get('/bai-viet/{slug}', 'show')->name('posts.show');
    Route::get('/danh-muc-bai-viet', 'categories')->name('posts.categories');
    Route::get('/danh-muc-bai-viet/{slug}', 'category')->name('posts.category');
});

/*
|--------------------------------------------------------------------------
| Routes Tài liệu khóa học
|--------------------------------------------------------------------------
| Download tài liệu với kiểm tra quyền truy cập
*/
Route::get('/course-material/{id}/download', function ($id) {
    $material = \App\Models\CourseMaterial::findOrFail($id);

    if (!$material->canDownload()) {
        abort(403, 'Không có quyền tải tài liệu này');
    }

    return response()->download(
        storage_path('app/' . $material->file_path),
        $material->file_name
    );
})->name('course.material.download');

/*
|--------------------------------------------------------------------------
| Routes SEO & Sitemap
|--------------------------------------------------------------------------
| Tối ưu hóa SEO và sitemap cho search engines
*/
Route::controller(SitemapController::class)->group(function () {
    Route::get('/sitemap.xml', 'index')->name('sitemap');
    Route::get('/robots.txt', 'robots')->name('robots');
});

/*
|--------------------------------------------------------------------------
| Routes Tìm kiếm
|--------------------------------------------------------------------------
| Tìm kiếm khóa học và bài viết
*/
Route::controller(SearchController::class)->group(function () {
    Route::get('/tim-kiem/khoa-hoc', 'courses')->name('search.courses');
    Route::get('/tim-kiem/bai-viet', 'posts')->name('search.posts');
});

/*
|--------------------------------------------------------------------------
| Routes Favicon Upload
|--------------------------------------------------------------------------
| Quản lý favicon của website
*/
Route::controller(FaviconController::class)->group(function () {
    Route::get('favicon', 'index')->name('favicon.index');
    Route::post('favicon', 'upload')->name('favicon.upload');
});

/*
|--------------------------------------------------------------------------
| Utility Routes
|--------------------------------------------------------------------------
| Các routes tiện ích cho quản trị và maintenance
*/

// Clear cache (production utility)
Route::post('/clear-cache', function () {
    \App\Providers\ViewServiceProvider::refreshCache('navigation');
    return response()->json(['message' => 'Cache cleared successfully!']);
})->name('clear.cache');

// Storage link utility
Route::get('/run-storage-link', function () {
    try {
        Artisan::call('storage:link');
        return response()->json(['message' => 'Storage linked successfully!'], 200);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
})->name('storage.link');

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
| API endpoints cho dashboard và realtime features
*/

// Realtime stats cho dashboard auto-refresh
Route::get('/api/realtime-stats', function () {
    try {
        $service = new App\Services\VisitorStatsService();
        $stats = $service->getRealtimeStats();

        return response()->json([
            'success' => true,
            'stats' => $stats,
            'timestamp' => now()->toISOString()
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'error' => 'Unable to fetch stats',
            'timestamp' => now()->toISOString()
        ], 500);
    }
})->middleware('auth')->name('api.realtime-stats');
