# Hệ thống QR Code Nhân viên

## Tổng quan

Hệ thống QR Code Nhân viên cho phép tạo trang thông tin cá nhân cho từng nhân viên với slug riêng và QR code để truy cập nhanh.

## Tính năng

### 1. **Trang thông tin nhân viên**
- URL: `/nhan-vien/{slug}`
- Thiết kế minimalism chuyên nghiệp với tone đỏ-trắng
- Responsive design hoàn hảo
- Hiển thị thông tin: tên, chức vụ, mô tả, điện thoại, email
- Gallery ảnh bổ sung với hover effects tinh tế
- Nút tải QR code với design clean

### 2. **Filament Admin Panel**
- Tự động tạo slug từ tên nhân viên
- Tự động tạo QR code khi lưu
- Nút "Tạo QR Code" để tạo lại
- Nút "Tải QR Code" để download
- Cột slug có thể copy

### 3. **QR Code System**
- Format SVG để tránh lỗi ImageMagick
- Tên file SEO-friendly
- Tự động tạo khi tạo/cập nhật nhân viên
- Có thể tải về trực tiếp

## Cách sử dụng

### Tạo nhân viên mới
1. Vào Admin Panel > Nhân viên > Tạo mới
2. Nhập tên nhân viên (slug sẽ tự động tạo)
3. Điền thông tin khác
4. Lưu - QR code sẽ tự động tạo

### Tạo QR code cho nhân viên hiện có
```bash
# Tạo QR code cho nhân viên chưa có
php artisan employee:generate-qr-codes

# Tạo lại QR code cho tất cả nhân viên
php artisan employee:generate-qr-codes --force
```

### Truy cập trang nhân viên
- URL: `http://domain.com/nhan-vien/{slug}`
- Ví dụ: `http://domain.com/nhan-vien/nguyen-van-tho`

### Tải QR code
- URL: `http://domain.com/nhan-vien/{slug}/qr-download`
- Hoặc dùng nút trong admin panel

## Cấu trúc Files

### Models
- `app/Models/Employee.php` - Model nhân viên với slug và QR methods

### Controllers
- `app/Http/Controllers/EmployeeController.php` - Xử lý hiển thị và download

### Services
- `app/Services/QrCodeService.php` - Service tạo và quản lý QR code

### Views
- `resources/views/employee/profile.blade.php` - Trang thông tin nhân viên

### Commands
- `app/Console/Commands/GenerateEmployeeQrCodes.php` - Command tạo QR code

### Observers
- `app/Observers/EmployeeObserver.php` - Tự động tạo QR code

## Routes

```php
// Trang thông tin nhân viên
Route::get('/nhan-vien/{slug}', [EmployeeController::class, 'profile'])
    ->name('employee.profile');

// Hiển thị QR code
Route::get('/nhan-vien/{slug}/qr-code', [EmployeeController::class, 'showQrCode'])
    ->name('employee.qr-code');

// Tải QR code
Route::get('/nhan-vien/{slug}/qr-download', [EmployeeController::class, 'downloadQrCode'])
    ->name('employee.qr-download');
```

## Database

### Migration
- `2024_12_01_000000_add_slug_to_employees_table.php`

### Cấu trúc bảng employees
```sql
- id
- name
- slug (unique)
- image_link
- position
- description (text)
- phone
- email
- qr_code
- order
- status
- created_at
- updated_at
```

## Packages sử dụng

- `simplesoftwareio/simple-qrcode` - Tạo QR code
- `intervention/image` - Xử lý ảnh (đã có sẵn)

## Lưu ý

1. QR code được lưu dạng SVG để tránh lỗi ImageMagick
2. Slug tự động tạo từ tên và đảm bảo unique
3. QR code tự động tạo khi tạo/cập nhật nhân viên
4. File QR code được lưu trong `storage/app/public/employees/qr-codes/`
5. Trang profile sử dụng layout shop với thiết kế glassmorphism

## Troubleshooting

### QR code không tạo được
- Kiểm tra package `simplesoftwareio/simple-qrcode` đã cài đặt
- Kiểm tra quyền ghi thư mục storage
- Xem log trong `storage/logs/laravel.log`

### Trang profile không hiển thị
- Kiểm tra route đã được định nghĩa
- Kiểm tra slug nhân viên có tồn tại
- Kiểm tra status nhân viên = 'active'
