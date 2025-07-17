# Global CTA Implementation - UPDATED

## Tổng quan
CTA đã được tích hợp hoàn toàn vào WebDesign system. Không còn trang quản lý riêng biệt.

## Các thay đổi đã thực hiện

### 1. Layout Changes
- **File**: `resources/views/layouts/shop.blade.php`
- **Thay đổi**: Thêm `@include('components.global.cta-section')` trước footer
- **Mục đích**: CTA hiển thị trên tất cả các trang sử dụng layout shop

### 2. Global CTA Component
- **File**: `resources/views/components/global/cta-section.blade.php`
- **Chức năng**: Component CTA tái sử dụng cho toàn bộ ứng dụng
- **Features**:
  - Lấy dữ liệu từ GlobalCtaService
  - Có thể bật/tắt hiển thị
  - Responsive design
  - Fallback values

### 3. WebDesign Integration
- **Đã gộp vào**: WebDesign system
- **Quản lý qua**: `/admin/manage-web-design`
- **Dữ liệu**: Lấy trực tiếp từ WebDesign model

### 4. Storefront Section Renderer Update
- **File**: `resources/views/components/storefront-section-renderer.blade.php`
- **Thay đổi**: Skip homepage_cta section vì đã được hiển thị trong layout chung
- **Lý do**: Tránh hiển thị CTA trùng lặp

### 5. Cache Observer Update
- **File**: `app/Observers/CacheObserver.php`
- **Thay đổi**: Thêm clear cache GlobalCtaService khi WebDesign được cập nhật
- **Mục đích**: Đảm bảo cache được cập nhật khi thay đổi cấu hình

### 6. CSS Styling
- **File**: `public/css/simple-storefront.css`
- **Thay đổi**: Thêm styling cho `.global-cta-section`
- **Features**: Background pattern, responsive design

### 7. WebDesign Management
- **Đã gộp vào**: `/admin/manage-web-design`
- **Vị trí**: CTA Toàn cục block trong Builder
- **Features**:
  - Drag & drop để sắp xếp thứ tự
  - Toggle bật/tắt hiển thị
  - Form cấu hình đầy đủ
  - Tích hợp với các section khác

## Cách sử dụng

### Quản lý CTA trong WebDesign
1. Truy cập Admin Panel
2. Vào "Quản lý nội dung" > "Giao diện trang chủ"
3. Tìm block "CTA Toàn cục"
4. Cấu hình nội dung CTA
5. Kéo thả để sắp xếp thứ tự
6. Lưu cấu hình

### Dữ liệu CTA
CTA sử dụng các field từ WebDesign model:
- `homepage_cta_enabled`: Bật/tắt hiển thị
- `homepage_cta_title`: Tiêu đề chính
- `homepage_cta_description`: Mô tả
- `homepage_cta_primary_button_text`: Text nút chính
- `homepage_cta_primary_button_url`: Link nút chính
- `homepage_cta_secondary_button_text`: Text nút phụ
- `homepage_cta_secondary_button_url`: Link nút phụ

### Cache Management
- Cache key: `global_cta_data`
- TTL: 3600 seconds (1 hour)
- Auto clear khi WebDesign được cập nhật

## Lợi ích

1. **Tái sử dụng**: CTA hiển thị trên tất cả các trang
2. **Quản lý tập trung**: Một nơi quản lý CTA cho toàn bộ website
3. **Performance**: Sử dụng cache để tối ưu hiệu suất
4. **Flexibility**: Có thể bật/tắt và cấu hình dễ dàng
5. **Consistency**: Giao diện nhất quán trên toàn bộ website

## Tương thích ngược
- Storefront vẫn hoạt động bình thường
- CTA không bị trùng lặp
- Các trang khác tự động có CTA mà không cần thay đổi code
