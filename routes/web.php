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
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CaptchaController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\CourseGroupController;
use App\Http\Controllers\FaviconController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\InstructorController;
use App\Http\Controllers\MainController;
use App\Http\Controllers\PostController;

use App\Http\Controllers\SearchController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\StudentController;
use App\Providers\ViewServiceProvider;
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
    Route::get('/khoa-hoc/{slug}', 'show')->name('courses.show');
    Route::get('/api/courses/search', 'searchSuggestions')->name('courses.search');
});

// Redirect route cũ về trang filter mới với query parameter
Route::get('/khoa-hoc/chuyen-muc/{slug}', function($slug) {
    return redirect()->route('courses.index', ['category' => $slug], 301);
})->name('courses.cat-category');

/*
|--------------------------------------------------------------------------
| Routes Authentication
|--------------------------------------------------------------------------
| Quản lý đăng nhập, đăng ký, đăng xuất cho học viên
*/
Route::controller(AuthController::class)->group(function () {
    Route::get('/dang-nhap', 'showLoginForm')->name('auth.login');
    Route::post('/dang-nhap', 'login');
    Route::get('/dang-ky', 'showRegisterForm')->name('auth.register');
    Route::post('/dang-ky', 'register');
    Route::post('/dang-xuat', 'logout')->name('auth.logout');
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
    Route::get('/hoc-vien/profile', 'profile')->name('students.profile')->middleware('student.auth');
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
| Routes Đóng góp ý kiến
|--------------------------------------------------------------------------
| Quản lý form đóng góp ý kiến từ khách hàng
*/
Route::controller(FeedbackController::class)->group(function () {
    Route::get('/dong-gop-y-kien', 'show')->name('feedback.show');
    Route::post('/dong-gop-y-kien', 'store')->name('feedback.store');
    Route::get('/cam-on-dong-gop', 'success')->name('feedback.success');
});

/*
|--------------------------------------------------------------------------
| Routes CAPTCHA
|--------------------------------------------------------------------------
| API endpoints cho CAPTCHA bảo mật
*/
Route::controller(CaptchaController::class)->group(function () {
    Route::get('/api/captcha/refresh', 'refresh')->name('captcha.refresh');
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
| Routes Album & PDF Timeline
|--------------------------------------------------------------------------
| API endpoints cho album PDF khóa học:
| - Tăng lượt xem
| - Tăng lượt tải
| - Lấy thông tin PDF
*/
Route::controller(AlbumController::class)->group(function () {
    Route::post('/api/albums/{album}/view', 'incrementView')->name('albums.view');
    Route::post('/api/albums/{album}/download', 'incrementDownload')->name('albums.download');
    Route::get('/api/albums/{album}/pdf', 'getPdf')->name('albums.pdf');
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
    Route::get('/danh-muc-bai-viet', 'categories')->name('posts.categories');
    Route::get('/danh-muc-bai-viet/{slug}', 'category')->name('posts.category');
    Route::get('/bai-viet/{slug}', 'show')->name('posts.show');

    // Redirect route cũ /bai-viet về trang filter mới
    Route::get('/bai-viet', function() {
        return redirect()->route('posts.categories');
    })->name('posts.index');
});

/*
|--------------------------------------------------------------------------
| Routes Tài liệu khóa học
|--------------------------------------------------------------------------
| Xem và download tài liệu với kiểm tra quyền truy cập
*/

// Route xem tài liệu
Route::get('/course-materials/{material}/view', function (\App\Models\CourseMaterial $material) {
    // Kiểm tra quyền truy cập
    if (!$material->canPreview()) {
        abort(403, 'Bạn cần đăng nhập để xem tài liệu này');
    }

    // Kiểm tra file tồn tại trong public disk
    if (!\Illuminate\Support\Facades\Storage::disk('public')->exists($material->file_path)) {
        abort(404, 'Tài liệu không tồn tại');
    }

    // Đảm bảo Content-Type đúng
    $contentType = $material->file_type;
    if ($contentType === 'pdf') {
        $contentType = 'application/pdf';
    } elseif (!str_contains($contentType, '/')) {
        // Nếu không có slash, thêm application/ prefix
        $contentType = 'application/' . $contentType;
    }

    // Trả về file để xem trong browser
    return response()->file(
        storage_path('app/public/' . $material->file_path),
        [
            'Content-Type' => $contentType,
            'Content-Disposition' => 'inline; filename="' . $material->file_name . '"'
        ]
    );
})->name('course-materials.view');

// Route download tài liệu
Route::get('/course-material/{id}/download', function ($id) {
    $material = \App\Models\CourseMaterial::findOrFail($id);

    // Kiểm tra quyền truy cập
    if (!$material->canDownload()) {
        abort(403, 'Bạn cần đăng nhập để tải tài liệu này');
    }

    // Kiểm tra file tồn tại trong public disk
    if (!\Illuminate\Support\Facades\Storage::disk('public')->exists($material->file_path)) {
        abort(404, 'Tài liệu không tồn tại');
    }

    return response()->download(
        storage_path('app/public/' . $material->file_path),
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

// Force refresh albums cache - for debugging media type transitions
Route::post('/api/force-refresh-albums-cache', function () {
    try {
        ViewServiceProvider::forceRebuildAlbumsCache();
        return response()->json([
            'success' => true,
            'message' => 'Albums cache refreshed successfully',
            'timestamp' => now()->toISOString()
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'error' => 'Failed to refresh cache: ' . $e->getMessage(),
            'timestamp' => now()->toISOString()
        ], 500);
    }
})->middleware('auth')->name('api.force-refresh-albums-cache');

