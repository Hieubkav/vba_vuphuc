<?php

use App\Http\Controllers\CourseController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\InstructorController;
use App\Http\Controllers\AlbumController;
use App\Http\Controllers\CourseGroupController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MainController;
use Illuminate\Support\Facades\Artisan;

Route::controller(MainController::class)->group(function () {
    Route::get('/', 'storeFront')->name('storeFront');
});






// Routes cho khóa học
Route::controller(CourseController::class)->group(function () {
    Route::get('/khoa-hoc', 'index')->name('courses.index');
    Route::get('/khoa-hoc/danh-muc/{slug}', 'category')->name('courses.category');
    Route::get('/khoa-hoc/chuyen-muc/{slug}', 'catCategory')->name('courses.cat-category');
    Route::get('/khoa-hoc/{slug}', 'show')->name('courses.show');
    Route::get('/api/courses/search', 'searchSuggestions')->name('courses.search');
});

// Routes cho học viên
Route::controller(StudentController::class)->group(function () {
    Route::get('/dang-ky-hoc-vien', 'register')->name('students.register');
    Route::post('/dang-ky-hoc-vien', 'store')->name('students.store');
    Route::get('/dang-ky-thanh-cong', 'success')->name('students.success');
    Route::post('/api/enroll-course', 'enrollCourse')->name('students.enroll');
    Route::get('/hoc-vien/profile', 'profile')->name('students.profile');
});

// Routes cho giảng viên
Route::controller(InstructorController::class)->group(function () {
    Route::get('/giang-vien/{slug}', 'show')->name('instructors.show');
});

// Routes cho nhóm khóa học
Route::controller(CourseGroupController::class)->group(function () {
    Route::get('/nhom-hoc-tap', 'index')->name('course-groups.index');
    Route::get('/nhom-hoc-tap/{slug}', 'show')->name('course-groups.show');
});

// Routes cho album
Route::controller(AlbumController::class)->group(function () {
    Route::post('/api/albums/{album}/view', 'incrementView')->name('albums.view');
    Route::post('/api/albums/{album}/download', 'incrementDownload')->name('albums.download');
    Route::get('/api/albums/{album}/images', 'getImages')->name('albums.images');
});

// Thêm route cho bài viết và dịch vụ
Route::controller(PostController::class)->group(function () {
    Route::get('/danh-muc-bai-viet/{slug}', 'category')->name('posts.category');
    Route::get('/danh-muc-bai-viet', 'categories')->name('posts.categories');
    Route::get('/bai-viet/{slug}', 'show')->name('posts.show');

    // Route tổng thể cho tất cả bài viết với filter
    Route::get('/bai-viet', 'index')->name('posts.index');

    // Redirect các route cũ về trang filter tổng thể
    Route::get('/dich-vu', function() {
        return redirect()->route('posts.index', ['type' => 'service']);
    })->name('posts.services');

    Route::get('/tin-tuc', function() {
        return redirect()->route('posts.index', ['type' => 'news']);
    })->name('posts.news');

    // Redirect route cũ về trang khóa học mới
    Route::get('/khoa-hoc-cu', function() {
        return redirect()->route('courses.index');
    })->name('posts.courses');
});

// Routes cho tài liệu khóa học
Route::get('/course-material/{id}/download', function($id) {
    $material = \App\Models\CourseMaterial::findOrFail($id);

    if (!$material->canDownload()) {
        abort(403, 'Không có quyền tải tài liệu này');
    }

    return response()->download(storage_path('app/' . $material->file_path), $material->file_name);
})->name('course.material.download');

// SEO routes
Route::controller(SitemapController::class)->group(function () {
    Route::get('/sitemap.xml', 'index')->name('sitemap');
    Route::get('/robots.txt', 'robots')->name('robots');
});

// Routes tìm kiếm
Route::controller(SearchController::class)->group(function () {
    Route::get('/tim-kiem/khoa-hoc', 'courses')->name('search.courses');
    Route::get('/tim-kiem/bai-viet', 'posts')->name('search.posts');
});

// Route clear cache (production utility)
Route::post('/clear-cache', function () {
    \App\Providers\ViewServiceProvider::refreshCache('navigation');
    return response()->json(['message' => 'Cache cleared successfully!']);
})->name('clear.cache');

Route::get('/run-storage-link', function () {
    try {
        Artisan::call('storage:link');
        return response()->json(['message' => 'Storage linked successfully!'], 200);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
});

// Test route cho course album component
Route::get('/test-course-album', function () {
    return view('test.course-album');
})->name('test.course-album');

// Test route cho course categories component
Route::get('/test-course-categories', function () {
    return view('test.course-categories');
})->name('test.course-categories');

// Test route cho course categories sections component
Route::get('/test-course-categories-sections', function () {
    return view('test.course-categories-sections');
})->name('test.course-categories-sections');

// Test route cho course groups component
Route::get('/test-course-groups', function () {
    return view('test.course-groups');
})->name('test.course-groups');

// Test route cho album timeline component
Route::get('/test-album-timeline', function () {
    return view('test.album-timeline');
})->name('test.album-timeline');

// Test route cho empty album timeline
Route::get('/test-album-timeline-empty', function () {
    return view('test.album-timeline', ['albums' => collect()]);
})->name('test.album-timeline-empty');

// Test route cho news fallback UI
Route::get('/test-news-fallback', function () {
    return view('test-news-fallback');
})->name('test.news-fallback');

// Test route cho partners component
Route::get('/test-partners', function () {
    return view('test-partners');
})->name('test.partners');

// Test route cho image optimization
Route::get('/test-image-optimization', function () {
    return view('test.image-optimization');
})->name('test.image-optimization');

// Test route cho simple image test
Route::get('/test-simple-image', function () {
    return view('test.simple-image-test');
})->name('test.simple-image');

// Test route cho lazy loading
Route::get('/test-lazy-loading', function () {
    return view('test-lazy-loading');
})->name('test.lazy-loading');










