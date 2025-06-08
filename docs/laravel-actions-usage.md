# Laravel Actions - ConvertImageToWebp

## Cài đặt

```bash
composer require lorisleiva/laravel-actions
```

## Action đã tạo

File: `app/Actions/ConvertImageToWebp.php`

### Tính năng:
- Chuyển ảnh sang WebP với 95% chất lượng
- Hỗ trợ resize ảnh
- Tên file SEO-friendly
- Fallback về file gốc nếu convert lỗi
- Tuân theo nguyên tắc KISS

## Cách sử dụng

### 1. Sử dụng static method (đơn giản nhất)

```php
use App\Actions\ConvertImageToWebp;

$result = ConvertImageToWebp::run(
    $file,              // UploadedFile
    'courses/thumbnails', // directory
    'custom-name',      // tên tùy chỉnh (optional)
    1200,              // width (optional)
    630                // height (optional)
);
```

### 2. Sử dụng instance method

```php
use App\Actions\ConvertImageToWebp;

$action = ConvertImageToWebp::make();
$result = $action->handle($file, 'courses/thumbnails', 'custom-name', 1200, 630);
```

### 3. Thay thế trong Filament Resources

**Trước (SimpleWebpService):**
```php
->saveUploadedFileUsing(function ($file, $get) {
    $webpService = app(\App\Services\SimpleWebpService::class);
    $title = $get('title') ?? 'post';
    
    $seoFileName = \App\Services\SeoImageService::createSeoFriendlyImageName($title, 'post');
    
    return $webpService->convertToWebP(
        $file,
        'posts/thumbnails',
        $seoFileName,
        1200,
        630
    );
})
```

**Sau (Laravel Action):**
```php
->saveUploadedFileUsing(function ($file, $get) {
    $title = $get('title') ?? 'post';
    
    $seoFileName = \App\Services\SeoImageService::createSeoFriendlyImageName($title, 'post');
    
    return \App\Actions\ConvertImageToWebp::run(
        $file,
        'posts/thumbnails',
        $seoFileName,
        1200,
        630
    );
})
```

## Ưu điểm của Laravel Actions

1. **Đơn giản hơn**: Không cần inject service
2. **Linh hoạt hơn**: Có thể dùng như static method hoặc instance
3. **Testable**: Dễ test hơn
4. **Reusable**: Có thể dùng ở nhiều nơi khác nhau
5. **Organized**: Tách biệt logic business khỏi controller/resource

## So sánh với Service

| Aspect | Service | Action |
|--------|---------|--------|
| Cách gọi | `app(Service::class)->method()` | `Action::run()` |
| Dependency Injection | Cần inject | Không cần |
| Static usage | Không | Có |
| Testing | Phức tạp hơn | Đơn giản hơn |
| Organization | Service layer | Action layer |

## Kết luận

Laravel Actions cung cấp cách tiếp cận hiện đại và đơn giản hơn cho việc tổ chức business logic. Action `ConvertImageToWebp` có thể thay thế hoàn toàn `SimpleWebpService` với cú pháp gọn gàng và dễ sử dụng hơn.
