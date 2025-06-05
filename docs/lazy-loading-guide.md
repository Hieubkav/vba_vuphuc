# Hướng dẫn Lazy Loading cho VBA Vũ Phúc

## Tổng quan

Hệ thống lazy loading đã được tích hợp toàn diện vào website VBA Vũ Phúc để tối ưu hóa hiệu suất tải trang và trải nghiệm người dùng.

## Các tính năng chính

### 1. Lazy Loading tự động
- Tất cả ảnh trong storefront components đều được lazy load
- Ảnh "above the fold" (2 ảnh đầu tiên) được ưu tiên tải ngay
- Intersection Observer API để detect khi ảnh vào viewport
- Blur placeholder effect trong khi tải

### 2. Timeout Management thông minh
- Fast fail timeout: 2 giây cho lần thử đầu tiên
- Standard timeout: 5 giây cho các lần retry
- Auto-remove loading spinner khi timeout
- Exponential backoff cho retry mechanism

### 3. Fallback UI thông minh
- Icon fallback tùy theo loại ảnh (khóa học, tin tức, đối tác, v.v.)
- Hiển thị lý do lỗi (timeout, không có ảnh, lỗi tải)
- Graceful degradation cho trình duyệt cũ

### 3. Performance optimizations
- Preload critical images
- Adaptive loading dựa trên connection speed
- Progressive loading cho galleries
- Smart caching

## Cách sử dụng

### 1. Blade Directives

#### @storefrontImage - Recommended
```blade
@storefrontImage([
    'src' => 'courses/course-1.jpg',
    'type' => 'course', // course, news, partner, album, testimonial
    'options' => [
        'alt' => 'Khóa học Excel',
        'class' => 'w-full h-full object-cover',
        'priority' => false // true cho ảnh above fold
    ]
])
```

#### @lazyImage - Basic usage
```blade
@lazyImage([
    'src' => 'path/to/image.jpg',
    'options' => [
        'alt' => 'Alt text',
        'class' => 'custom-class',
        'priority' => false,
        'blur' => true
    ]
])
```

#### @lazyImageWithFallback - Custom fallback
```blade
@lazyImageWithFallback([
    'src' => 'path/to/image.jpg',
    'options' => ['alt' => 'Alt text'],
    'fallback' => [
        'icon' => 'fas fa-image',
        'text' => 'Ảnh',
        'bg' => 'bg-gray-100',
        'textColor' => 'text-gray-400'
    ]
])
```

### 2. Manual HTML

```html
<img data-src="{{ asset('storage/image.jpg') }}"
     alt="Description"
     class="lazy-loading course-image"
     loading="lazy"
     style="opacity: 0; transition: opacity 0.3s ease, filter 0.3s ease, transform 0.3s ease;"
     onerror="if(window.storefrontLazyLoader) { window.storefrontLazyLoader.showFallback(this); }">
```

### 3. JavaScript API

```javascript
// Force load một ảnh
window.storefrontLazyLoader.forceLoadImage(imgElement);

// Force load với custom timeout
window.storefrontLazyLoader.forceLoadImageWithTimeout(imgElement, 3000);

// Refresh lazy loading (sau khi thêm nội dung mới)
window.storefrontLazyLoader.refresh();

// Check image status
const status = window.storefrontLazyLoader.getImageStatus(imgElement);
// Returns: 'loaded', 'loading', 'error', 'pending'

// Get loading statistics
const stats = window.storefrontLazyLoader.getLoadingStats();
console.log(stats); // {total, loaded, error, loading, pending}

// Cleanup resources
window.storefrontLazyLoader.cleanup();

// Check if lazy loader is available
if (window.storefrontLazyLoader) {
    console.log('Lazy loading is active');
}
```

## Image Types và Fallbacks

| Type | Icon | Text | Background | Use Case |
|------|------|------|------------|----------|
| course | fas fa-graduation-cap | Khóa học | bg-red-50 | Course thumbnails |
| news | fas fa-newspaper | Tin tức | bg-blue-50 | News/blog posts |
| partner | fas fa-handshake | Đối tác | bg-green-50 | Partner logos |
| album | fas fa-images | Album | bg-purple-50 | Photo galleries |
| testimonial | fas fa-user | Người dùng | bg-gray-50 | User avatars |
| default | fas fa-image | Ảnh | bg-gray-100 | General images |

## Configuration

### Intersection Observer Settings
```javascript
{
    rootMargin: '50px 0px 100px 0px', // Load 50px before entering viewport
    threshold: 0.01,
    enableBlur: true,
    fadeInDuration: 300,
    retryAttempts: 3,
    retryDelay: 1000,
    loadingTimeout: 5000, // 5 giây timeout cho mỗi ảnh
    fastFailTimeout: 2000, // 2 giây cho lần thử đầu tiên
    maxConcurrentLoads: 6, // Tối đa 6 ảnh load cùng lúc
    timeoutMessages: {
        'timeout': 'Tải chậm',
        'no-src': 'Không có ảnh',
        'stuck-loading': 'Lỗi tải',
        'error': 'Lỗi ảnh'
    }
}
```

### Adaptive Loading
Hệ thống tự động điều chỉnh chất lượng và batch size dựa trên connection speed:
- slow-2g: quality 60%, maxWidth 480px, batchSize 2
- 2g: quality 70%, maxWidth 768px, batchSize 3
- 3g: quality 80%, maxWidth 1024px, batchSize 4
- 4g: quality 90%, maxWidth 1920px, batchSize 6

## Components đã tích hợp

### Storefront Components
- ✅ hero-banner.blade.php
- ✅ course-posts.blade.php
- ✅ blog-posts.blade.php
- ✅ partners.blade.php
- ✅ album-timeline.blade.php
- ✅ testimonials.blade.php
- ✅ course-categories.blade.php
- ✅ course-card.blade.php
- ✅ course-category-section.blade.php

### Layout
- ✅ layouts/shop.blade.php - Tích hợp scripts và config

## CSS Classes

### Loading States
- `.lazy-loading` - Ảnh đang lazy load
- `.lazy-loaded` - Ảnh đã load xong
- `.lazy-error` - Ảnh bị lỗi

### Type-specific Classes
- `.hero-image-main.lazy-loading` - Hero images
- `.course-image.lazy-loading` - Course images
- `.news-image.lazy-loading` - News images
- `.partner-image.lazy-loading` - Partner logos
- `.album-image.lazy-loading` - Album photos

## Performance Tips

### 1. Priority Loading
- Đặt `priority: true` cho 2-3 ảnh đầu tiên (above fold)
- Các ảnh priority sẽ load ngay lập tức

### 2. Image Optimization
- Sử dụng WebP format khi có thể
- Compress ảnh trước khi upload
- Sử dụng responsive images với srcset

### 3. Preloading
```blade
@preloadImages([
    ['path' => 'hero-image.jpg', 'as' => 'image', 'type' => 'image/webp'],
    ['path' => 'logo.jpg', 'as' => 'image', 'type' => 'image/webp']
])
```

## Testing

### Test Page
Truy cập `/test-lazy-loading` để xem demo và test performance.

### Browser DevTools
1. Mở Network tab
2. Reload trang
3. Scroll xuống để xem ảnh load theo yêu cầu
4. Check Console cho lazy loading logs

### Performance Metrics
- Theo dõi số lượng ảnh total/loaded/lazy
- Monitor network requests
- Check LCP (Largest Contentful Paint) improvement

## Troubleshooting

### Ảnh không lazy load
1. Check console cho errors
2. Verify `window.storefrontLazyLoader` exists
3. Check image path và permissions
4. Verify Intersection Observer support

### Fallback không hiển thị
1. Check CSS cho `.fallback-icon`
2. Verify onerror handler
3. Check image path validity

### Performance issues
1. Reduce number of priority images
2. Optimize image sizes
3. Check network throttling
4. Monitor memory usage

## Browser Support

- ✅ Chrome 58+
- ✅ Firefox 55+
- ✅ Safari 12.1+
- ✅ Edge 79+
- ⚠️ IE 11 (fallback mode - loads all images immediately)

## Files Structure

```
public/js/
├── storefront-lazy-loading.js    # Main lazy loading logic
└── smart-lazy-loading.js         # Legacy support

resources/css/
└── performance.css               # Lazy loading styles

app/Services/
└── LazyLoadService.php          # Backend service

app/Providers/
└── LazyLoadServiceProvider.php  # Blade directives

resources/views/
├── layouts/shop.blade.php       # Main layout integration
└── test-lazy-loading.blade.php  # Test page
```
