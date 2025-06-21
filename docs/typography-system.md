# Hệ thống Typography - VBA Vũ Phúc

## Tổng quan
Hệ thống typography được thiết kế để đảm bảo tính thống nhất về font chữ, cỡ chữ và spacing trên toàn bộ website.

## Font Families

### Primary Fonts
- **Heading**: `font-heading` - Montserrat, Inter, system fonts
- **Body**: `font-body` - Inter, system fonts  
- **Sans**: `font-sans` - Inter, system fonts (default)

### Legacy Fonts (vẫn hỗ trợ)
- `font-montserrat` - Montserrat
- `font-open-sans` - Open Sans

## Typography Scale

### Display Sizes (Hero, Banner lớn)
```html
<!-- Display 2XL: 72px, font-weight: 800 -->
<h1 class="text-display-2xl">Hero Title</h1>

<!-- Display XL: 60px, font-weight: 800 -->
<h1 class="text-display-xl">Large Banner</h1>

<!-- Display LG: 48px, font-weight: 700 -->
<h1 class="text-display-lg">Medium Banner</h1>
```

### Heading Sizes (Tiêu đề section)
```html
<!-- Heading XL: 36px, font-weight: 700 -->
<h2 class="text-heading-xl">Section Title</h2>

<!-- Heading LG: 30px, font-weight: 600 -->
<h2 class="text-heading-lg">Subsection Title</h2>

<!-- Heading MD: 24px, font-weight: 600 -->
<h3 class="text-heading-md">Card Title</h3>

<!-- Heading SM: 20px, font-weight: 600 -->
<h4 class="text-heading-sm">Small Title</h4>

<!-- Heading XS: 18px, font-weight: 600 -->
<h5 class="text-heading-xs">Tiny Title</h5>
```

### Body Sizes (Nội dung)
```html
<!-- Body XL: 20px -->
<p class="text-body-xl">Large body text</p>

<!-- Body LG: 18px -->
<p class="text-body-lg">Medium body text</p>

<!-- Body MD: 16px (default) -->
<p class="text-body-md">Normal body text</p>

<!-- Body SM: 14px -->
<p class="text-body-sm">Small body text</p>

<!-- Body XS: 12px -->
<p class="text-body-xs">Tiny body text</p>
```

### Caption Sizes (Chú thích, meta)
```html
<!-- Caption LG: 14px, font-weight: 500 -->
<span class="text-caption-lg">Large caption</span>

<!-- Caption MD: 12px, font-weight: 500 -->
<span class="text-caption-md">Normal caption</span>

<!-- Caption SM: 11px, font-weight: 500 -->
<span class="text-caption-sm">Small caption</span>
```

## Component Classes

### Hero/Banner Typography
```html
<h1 class="hero-title">
    Responsive hero title
    <!-- Mobile: 48px, Tablet: 60px, Desktop: 72px -->
</h1>
```

### Section Headings
```html
<h2 class="section-title">
    Responsive section title
    <!-- Mobile: 30px, Desktop: 36px -->
</h2>
```

### Card Titles
```html
<h3 class="card-title">
    Responsive card title
    <!-- Mobile: 20px, Desktop: 24px -->
</h3>
```

### Descriptions
```html
<p class="subtitle">
    Responsive subtitle/description
    <!-- Mobile: 18px, Desktop: 20px -->
</p>
```

### Body Text
```html
<p class="body-text">Standard body text (16px)</p>
```

### Captions
```html
<span class="caption-text">Small caption text (12px)</span>
```

### Button Text
```html
<button class="btn-text">Normal Button</button>
<button class="btn-text-lg">Large Button</button>
```

### Navigation
```html
<a class="nav-text">Navigation Link</a>
```

### Badges/Meta
```html
<span class="badge-text">BADGE TEXT</span>
```

## Ví dụ sử dụng trong Components

### Hero Banner
```html
<div class="hero-banner">
    <h1 class="hero-title text-white">
        Bắt đầu hành trình với VBA Vũ Phúc
    </h1>
    <p class="subtitle text-white/90 mt-4">
        Khám phá các khóa học VBA chất lượng cao
    </p>
</div>
```

### Course Card
```html
<article class="course-card">
    <h3 class="card-title text-gray-900">
        {{ $course->title }}
    </h3>
    <p class="body-text text-gray-600 mt-2">
        {{ $course->description }}
    </p>
    <span class="caption-text text-gray-500 mt-4">
        Cập nhật: {{ $course->updated_at }}
    </span>
</article>
```

### Section
```html
<section class="py-16">
    <h2 class="section-title text-center mb-8">
        Khóa học nổi bật
    </h2>
    <p class="subtitle text-center text-gray-600 mb-12">
        Các khóa học được yêu thích nhất
    </p>
</section>
```

## Migration Guide

### Thay thế các class cũ:
- `text-3xl font-bold` → `hero-title`
- `text-2xl font-semibold` → `section-title`
- `text-xl font-medium` → `card-title`
- `text-lg text-gray-600` → `subtitle`
- `text-base` → `body-text`
- `text-sm text-gray-500` → `caption-text`

### Ưu tiên sử dụng:
1. **Component classes** cho các pattern thường dùng
2. **Typography scale** cho các trường hợp đặc biệt
3. **Legacy classes** chỉ khi cần thiết

## Best Practices

1. **Consistency**: Luôn sử dụng component classes trước
2. **Responsive**: Component classes đã responsive sẵn
3. **Accessibility**: Đã tối ưu line-height và contrast
4. **Performance**: Giảm CSS bundle size
5. **Maintainability**: Dễ dàng thay đổi toàn bộ hệ thống
