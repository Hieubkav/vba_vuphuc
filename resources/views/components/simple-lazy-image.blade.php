@props([
    'src' => '',
    'alt' => '',
    'class' => '',
    'width' => null,
    'height' => null,
    'priority' => false, // true cho ảnh above fold
    'fallbackIcon' => 'fas fa-image',
    'fallbackType' => 'default'
])

@php
    // Xử lý đường dẫn ảnh
    $imagePath = $src;
    if (str_starts_with($src, asset('storage/'))) {
        $imagePath = str_replace(asset('storage/'), '', $src);
    } elseif (str_starts_with($src, 'storage/')) {
        $imagePath = str_replace('storage/', '', $src);
    }
    
    // Kiểm tra ảnh tồn tại
    $imageExists = !empty($imagePath) && \Storage::exists('public/' . $imagePath);
    $imageUrl = $imageExists ? asset('storage/' . $imagePath) : null;
    
    // Fallback icons theo type
    $fallbackIcons = [
        'course' => 'fas fa-graduation-cap',
        'news' => 'fas fa-newspaper', 
        'partner' => 'fas fa-handshake',
        'album' => 'fas fa-images',
        'testimonial' => 'fas fa-quote-left',
        'default' => 'fas fa-image'
    ];
    
    $fallbackIcon = $fallbackIcons[$fallbackType] ?? $fallbackIcons['default'];
@endphp

<div class="simple-lazy-image-container relative overflow-hidden">
    @if($imageExists)
        <img 
            src="{{ $imageUrl }}"
            alt="{{ $alt }}"
            class="{{ $class }}"
            @if($width) width="{{ $width }}" @endif
            @if($height) height="{{ $height }}" @endif
            loading="{{ $priority ? 'eager' : 'lazy' }}"
            decoding="async"
            onerror="handleSimpleImageError(this)"
            style="transition: opacity 0.3s ease;"
        >
        
        {{-- Fallback UI - ẩn mặc định --}}
        <div class="fallback-placeholder w-full h-full bg-gray-50 flex items-center justify-center absolute inset-0" 
             style="display: none;">
            <i class="{{ $fallbackIcon }} text-2xl text-gray-400"></i>
        </div>
    @else
        {{-- Hiển thị fallback ngay khi không có ảnh --}}
        <div class="fallback-placeholder w-full h-full bg-gray-50 flex items-center justify-center">
            <i class="{{ $fallbackIcon }} text-2xl text-gray-400"></i>
        </div>
    @endif
</div>

@once
@push('scripts')
<script>
// Simple image error handler
function handleSimpleImageError(img) {
    console.log('Image error:', img.src);
    
    // Ẩn ảnh lỗi
    img.style.display = 'none';
    
    // Hiển thị fallback
    const fallback = img.nextElementSibling;
    if (fallback && fallback.classList.contains('fallback-placeholder')) {
        fallback.style.display = 'flex';
        fallback.style.opacity = '0';
        setTimeout(() => {
            fallback.style.transition = 'opacity 0.3s ease';
            fallback.style.opacity = '1';
        }, 50);
    }
}
</script>
@endpush
@endonce
