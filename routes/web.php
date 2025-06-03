<?php

use App\Http\Controllers\EcomerceController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\SitemapController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MainController;
use Illuminate\Support\Facades\Artisan;

Route::controller(MainController::class)->group(function () {
    Route::get('/', 'storeFront')->name('storeFront');
});

Route::controller(EcomerceController::class)->group(function () {
    Route::get('/ban-hang', 'index')->name('ecomerce.index');
});

// Thêm route cho sản phẩm và danh mục
Route::controller(ProductController::class)->group(function () {
    Route::get('/danh-muc/{slug}', 'category')->name('products.category');
    Route::get('/danh-muc', 'categories')->name('products.categories');
    Route::get('/san-pham/{slug}', 'show')->name('products.show');
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

    Route::get('/khoa-hoc', function() {
        return redirect()->route('posts.index', ['type' => 'course']);
    })->name('posts.courses');
});

// Thêm route cho nhân viên
Route::controller(EmployeeController::class)->group(function () {
    // Route danh sách nhân viên - yêu cầu đăng nhập
    Route::get('/nhan-vien', 'index')->name('employee.index')->middleware('auth');

    // Route profile công khai
    Route::get('/nhan-vien/{slug}', 'profile')->name('employee.profile');
    Route::get('/nhan-vien/{slug}/qr-code', 'showQrCode')->name('employee.qr-code');
    Route::get('/nhan-vien/{slug}/qr-download', 'downloadQrCode')->name('employee.qr-download');
});

// SEO routes
Route::controller(SitemapController::class)->group(function () {
    Route::get('/sitemap.xml', 'index')->name('sitemap');
    Route::get('/robots.txt', 'robots')->name('robots');
});

// Routes tìm kiếm
Route::controller(SearchController::class)->group(function () {
    Route::get('/tim-kiem/san-pham', 'products')->name('products.search');
    Route::get('/tim-kiem/bai-viet', 'posts')->name('posts.search');
});

// Route test navbar
Route::get('/test-navbar', function () {
    return view('test-navbar');
})->name('test.navbar');

// Route test menu update
Route::get('/test-menu', function () {
    return view('test-menu');
})->name('test.menu');



// Route clear cache
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

// Debug route - tạm thời
Route::get('/debug-products', function () {
    $product = \App\Models\Product::with('images')->where('is_hot', true)->first();
    if ($product) {
        $data = [
            'product_name' => $product->name,
            'images_count' => $product->images->count(),
            'images' => $product->images->map(function($image) {
                return [
                    'id' => $image->id,
                    'image_link' => $image->image_link,
                    'is_main' => $image->is_main,
                    'status' => $image->status,
                    'order' => $image->order
                ];
            }),
            'getProductImageUrl_result' => getProductImageUrl($product)
        ];
        return response()->json($data, 200, [], JSON_PRETTY_PRINT);
    }
    return response()->json(['message' => 'No hot products found'], 404);
});






