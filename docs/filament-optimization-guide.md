# 🚀 Hướng dẫn Tối ưu hóa Filament Admin Panel

## 📋 Tổng quan

Service tối ưu hóa Filament được thiết kế để cải thiện hiệu suất admin panel mà không gây lỗi dự án. Service này có thể tái sử dụng cho nhiều dự án Laravel + Filament.

## ✨ Tính năng chính

### 🔧 FilamentOptimizationService
- **Query Caching**: Cache kết quả query thông minh
- **Eager Loading**: Tự động tối ưu N+1 queries
- **Memory Management**: Quản lý memory hiệu quả
- **Performance Monitoring**: Giám sát hiệu suất

### 🎯 OptimizedFilamentResource Trait
- **Tự động tối ưu**: Áp dụng optimization mà không cần viết lại code
- **Table Optimization**: Tối ưu table listing
- **Form Optimization**: Tối ưu form loading
- **Search Optimization**: Tối ưu tìm kiếm

### ⚡ FilamentOptimizationMiddleware
- **Request Optimization**: Tối ưu từng request
- **Memory Monitoring**: Giám sát memory usage
- **Performance Logging**: Log các request chậm

## 🚀 Cài đặt và Cấu hình

### 1. Đăng ký Service Provider

Thêm vào `config/app.php`:

```php
'providers' => [
    // ...
    App\Providers\FilamentOptimizationServiceProvider::class,
],
```

### 2. Publish Config

```bash
php artisan vendor:publish --tag=filament-optimization-config
```

### 3. Cấu hình Environment

Thêm vào `.env`:

```env
# Filament Optimization
FILAMENT_QUERY_CACHE=true
FILAMENT_CACHE_DURATION=300
FILAMENT_EAGER_LOADING=true
FILAMENT_MEMORY_OPTIMIZATION=true
FILAMENT_ASSET_OPTIMIZATION=true
FILAMENT_PAGINATION_SIZE=25
```

### 4. Đăng ký Command

Command đã được tự động đăng ký. Sử dụng:

```bash
# Tối ưu toàn diện
php artisan filament:optimize

# Xóa cache
php artisan filament:optimize --clear-cache

# Phân tích hiệu suất
php artisan filament:optimize --analyze

# Tự động sửa lỗi
php artisan filament:optimize --fix

# Xem thống kê
php artisan filament:optimize --stats
```

## 📖 Cách sử dụng

### 1. Sử dụng OptimizedFilamentResource Trait

```php
<?php

namespace App\Filament\Admin\Resources;

use App\Traits\OptimizedFilamentResource;
use Filament\Resources\Resource;

class YourResource extends Resource
{
    use OptimizedFilamentResource;

    // Override các method để tối ưu
    protected static function getTableColumns(): array
    {
        return ['id', 'name', 'status', 'created_at'];
    }

    protected static function getFormRelationships(): array
    {
        return ['category', 'images'];
    }

    protected static function getSearchableColumns(): array
    {
        return ['name', 'description'];
    }
}
```

### 2. Sử dụng FilamentOptimizationService trực tiếp

```php
<?php

use App\Services\FilamentOptimizationService;

class YourController
{
    public function __construct(
        private FilamentOptimizationService $optimizationService
    ) {}

    public function index()
    {
        // Cache query
        $posts = $this->optimizationService->cacheQuery(
            'posts_list',
            function() {
                return Post::with('category')->get();
            },
            300 // 5 minutes
        );

        // Tối ưu select options
        $categories = $this->optimizationService->optimizeSelectOptions(
            Category::query(),
            'id',
            'name'
        );

        return view('admin.posts.index', compact('posts', 'categories'));
    }
}
```

### 3. Tối ưu Table Query

```php
public static function getEloquentQuery(): Builder
{
    $query = parent::getEloquentQuery();
    
    $optimizationService = app(FilamentOptimizationService::class);
    
    return $optimizationService->optimizeTableQuery($query, [
        'id', 'name', 'status', 'created_at'
    ]);
}
```

## ⚙️ Cấu hình nâng cao

### 1. Tùy chỉnh Cache

```php
// config/filament-optimization.php
'cache' => [
    'store' => 'redis', // hoặc 'file', 'database'
    'prefix' => 'filament_opt',
    'tags' => [
        'resources' => 'filament_resources',
        'queries' => 'filament_queries',
    ],
],
```

### 2. Tùy chỉnh Memory

```php
'memory' => [
    'enable_optimization' => true,
    'memory_limit' => '512M',
    'enable_garbage_collection' => true,
],
```

### 3. Tùy chỉnh cho từng Resource

```php
'resources' => [
    'posts' => [
        'table_columns' => ['id', 'title', 'status'],
        'eager_load' => ['category', 'images'],
        'cache_duration' => 600,
    ],
],
```

## 📊 Monitoring và Debug

### 1. Xem thống kê hiệu suất

```bash
php artisan filament:optimize --stats
```

### 2. Phân tích vấn đề

```bash
php artisan filament:optimize --analyze
```

### 3. Log Performance

Các request chậm sẽ được log tự động vào `storage/logs/laravel.log`:

```
[2024-01-01 10:00:00] local.INFO: Filament Performance Metrics
{
    "url": "/admin/posts",
    "execution_time_ms": 1500.25,
    "memory_used_mb": 75.5,
    "user_id": 1
}
```

## 🔧 Troubleshooting

### 1. Cache không hoạt động

Kiểm tra:
- Redis/Cache driver có hoạt động không
- Quyền ghi file cache
- Cấu hình `FILAMENT_QUERY_CACHE=true`

### 2. Memory limit

Tăng memory limit:
```env
FILAMENT_MEMORY_LIMIT=512M
```

### 3. Slow queries

Enable query logging:
```env
FILAMENT_LOG_SLOW_QUERIES=true
FILAMENT_MAX_QUERY_TIME=500
```

## 🎯 Best Practices

### 1. Sử dụng Trait cho tất cả Resources

```php
class PostResource extends Resource
{
    use OptimizedFilamentResource;
    // ...
}
```

### 2. Định nghĩa Table Columns cụ thể

```php
protected static function getTableColumns(): array
{
    // Chỉ select cột cần thiết
    return ['id', 'name', 'status', 'created_at'];
}
```

### 3. Eager Load Relationships

```php
protected static function getFormRelationships(): array
{
    return [
        'category' => function($query) {
            $query->select(['id', 'name']);
        }
    ];
}
```

### 4. Cache Select Options

```php
$options = $this->optimizationService->optimizeSelectOptions(
    Category::query(),
    'id',
    'name'
);
```

### 5. Clear Cache khi cần

```php
// Clear cache cho resource cụ thể
YourResource::clearResourceCache();

// Clear toàn bộ cache
$optimizationService->clearCache();
```

## 📈 Kết quả mong đợi

Sau khi áp dụng optimization:

- **Tốc độ load**: Cải thiện 50-80%
- **Memory usage**: Giảm 30-50%
- **Database queries**: Giảm N+1 queries
- **User experience**: Mượt mà hơn đáng kể

## 🔄 Maintenance

### 1. Chạy optimization định kỳ

```bash
# Thêm vào crontab
0 2 * * * cd /path/to/project && php artisan filament:optimize --fix
```

### 2. Monitor performance

```bash
# Kiểm tra stats hàng ngày
php artisan filament:optimize --stats
```

### 3. Clear cache khi deploy

```bash
php artisan filament:optimize --clear-cache
```

---

*Tài liệu này được cập nhật thường xuyên. Vui lòng kiểm tra phiên bản mới nhất.*
