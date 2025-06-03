# Changelog

Tất cả các thay đổi quan trọng của dự án Vũ Phúc sẽ được ghi lại trong file này.

Định dạng dựa trên [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
và dự án này tuân theo [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

### Added
- Tổ chức lại cấu trúc tài liệu dự án
- Thêm hướng dẫn cài đặt chi tiết
- Thêm hướng dẫn phát triển và deployment
- Thêm tài liệu API đầy đủ
- Thêm hướng dẫn đóng góp (CONTRIBUTING.md)

### Changed
- Cải thiện README.md với thông tin đầy đủ hơn
- Tổ chức lại các file markdown vào thư mục docs/
- Đổi tên các file để dễ hiểu và nhất quán

### Fixed
- Sửa lỗi composer update trên Windows
- Tối ưu hóa cấu trúc thư mục dự án

## [1.0.0] - 2024-01-15

### Added
- Khởi tạo dự án Laravel 10 với Filament Admin Panel
- Tích hợp Livewire cho real-time updates
- Hệ thống quản lý bài viết với SEO tự động
- Hệ thống quản lý nhân viên với QR code
- Tối ưu hóa hình ảnh tự động (WebP conversion)
- Responsive design với Tailwind CSS
- Dashboard với thống kê real-time
- Hệ thống tìm kiếm nâng cao
- Observer pattern cho file management
- Multi-language support (Tiếng Việt)

### Features
- **Admin Panel**: Quản trị toàn diện với Filament
- **SEO Optimization**: Tự động tạo meta tags và sitemap
- **Image Processing**: Chuyển đổi ảnh sang WebP
- **QR Code Integration**: Tạo QR code cho nhân viên
- **Real-time Dashboard**: Cập nhật thống kê thời gian thực
- **Responsive Design**: Tối ưu cho mọi thiết bị
- **Search System**: Tìm kiếm thông minh với suggestions

### Technical
- Laravel 10.x
- Filament 3.x
- Livewire 3.x
- Tailwind CSS
- Alpine.js
- MySQL/PostgreSQL
- Redis (optional)

---

## Quy tắc Changelog

### Types of changes
- `Added` cho các tính năng mới
- `Changed` cho thay đổi trong tính năng hiện có
- `Deprecated` cho tính năng sẽ bị loại bỏ
- `Removed` cho tính năng đã bị loại bỏ
- `Fixed` cho bug fixes
- `Security` cho các vấn đề bảo mật

### Format
```markdown
## [Version] - YYYY-MM-DD

### Added
- Tính năng mới được thêm

### Changed
- Thay đổi trong tính năng hiện có

### Fixed
- Bug được sửa
```
