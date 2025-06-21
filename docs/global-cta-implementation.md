# Global CTA Implementation

## Tổng quan
Đã di chuyển CTA section từ chỉ dành cho storefront sang layout chung để sử dụng cho toàn bộ các giao diện.

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

### 3. Global CTA Service
- **File**: `app/Services/GlobalCtaService.php`
- **Chức năng**: Quản lý dữ liệu CTA với cache
- **Methods**:
  - `getCtaData()`: Lấy dữ liệu CTA với cache
  - `clearCache()`: Xóa cache CTA
  - `isEnabled()`: Kiểm tra CTA có được bật không

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

### 7. Admin Management Page
- **File**: `app/Filament/Admin/Pages/ManageGlobalCta.php`
- **Chức năng**: Trang quản lý CTA riêng biệt trong admin
- **Features**:
  - Form cấu hình CTA
  - Preview CTA real-time
  - Bật/tắt hiển thị
  - Cấu hình nút hành động

- **File**: `resources/views/filament/admin/pages/manage-global-cta.blade.php`
- **Chức năng**: Giao diện quản lý CTA với preview

## Cách sử dụng

### Quản lý CTA trong Admin
1. Truy cập Admin Panel
2. Vào "Quản lý nội dung" > "CTA Toàn cục"
3. Cấu hình nội dung CTA
4. Bật/tắt hiển thị
5. Lưu cấu hình

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
