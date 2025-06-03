# Dashboard Realtime - Hướng dẫn sử dụng

## Tổng quan
Dashboard realtime đã được tích hợp vào admin panel với các tính năng:

### ✨ Tính năng chính
- **Cập nhật tự động**: Tất cả widgets tự động refresh mỗi 10-30 giây
- **Thống kê realtime**: Hiển thị dữ liệu thời gian thực
- **Biểu đồ tương tác**: Charts với animation và tooltip
- **Hoạt động gần đây**: Theo dõi các thay đổi mới nhất
- **Responsive design**: Tương thích mọi thiết bị

### 📊 Các Widget có sẵn

#### 1. RealtimeStats (Thống kê realtime)
- Đơn hàng hôm nay vs hôm qua
- Doanh thu hôm nay vs hôm qua  
- Đơn hàng chờ xử lý
- Sản phẩm sắp hết hàng
- Tổng sản phẩm và khách hàng
- **Refresh**: Mỗi 10 giây

#### 2. SalesChart (Biểu đồ doanh thu)
- Doanh thu theo ngày
- Có thể filter theo khoảng thời gian
- **Refresh**: Mỗi 30 giây

#### 3. OrdersPerDayChart (Đơn hàng theo ngày)
- Số lượng đơn hàng theo ngày
- Biểu đồ line chart
- **Refresh**: Mỗi 30 giây

#### 4. ProductsChart (Sản phẩm theo danh mục)
- Phân bố sản phẩm theo category
- Doughnut chart với màu sắc
- **Refresh**: Mỗi 30 giây

#### 5. OrdersChart (Trạng thái đơn hàng)
- Phân bố đơn hàng theo status
- Pie chart với màu sắc theo trạng thái
- **Refresh**: Mỗi 30 giây

#### 6. LatestOrders (Đơn hàng mới nhất)
- 10 đơn hàng gần nhất
- Hiển thị thông tin chi tiết
- Link đến trang chi tiết đơn hàng
- **Refresh**: Mỗi 30 giây

#### 7. TopProducts (Sản phẩm bán chạy)
- Sản phẩm có doanh số cao nhất
- Hiển thị số lượng bán và doanh thu
- **Refresh**: Mỗi 30 giây

#### 8. RecentActivity (Hoạt động gần đây)
- Đơn hàng mới
- Sản phẩm mới
- Khách hàng mới
- Cảnh báo sắp hết hàng
- **Refresh**: Mỗi 15 giây

## 🚀 Cách sử dụng

### Truy cập Dashboard
```
http://127.0.0.1:8000/admin
```

### Bộ lọc thời gian
- Sử dụng form filter ở đầu trang
- Chọn "Từ ngày" và "Đến ngày"
- Các biểu đồ sẽ tự động cập nhật theo filter

### Tương tác với widgets
- **Hover effects**: Widgets có hiệu ứng khi hover
- **Click vào stats**: Một số stats có thể click để xem chi tiết
- **Loading animation**: Hiển thị khi đang refresh dữ liệu

## 🛠️ Commands hỗ trợ

### Tạo dữ liệu mẫu
```bash
php artisan create:sample-data
```

### Mô phỏng dữ liệu realtime
```bash
# Chạy trong 60 giây (mặc định)
php artisan simulate:realtime-data

# Chạy trong 30 giây
php artisan simulate:realtime-data --duration=30
```

## ⚙️ Cấu hình

### Thay đổi thời gian refresh
Trong mỗi widget class, sửa thuộc tính:
```php
protected static ?string $pollingInterval = '30s'; // 30 giây
protected static ?string $pollingInterval = '1m';  // 1 phút
protected static ?string $pollingInterval = null;  // Tắt auto refresh
```

### Thêm widget mới
1. Tạo widget class trong `app/Filament/Admin/Widgets/`
2. Thêm vào `Dashboard.php`:
```php
public function getWidgets(): array
{
    return [
        // ... existing widgets
        \App\Filament\Admin\Widgets\YourNewWidget::class,
    ];
}
```

### Tùy chỉnh layout
Trong `Dashboard.php`:
```php
public function getColumns(): int | string | array
{
    return [
        'sm' => 1,  // Mobile: 1 cột
        'md' => 2,  // Tablet: 2 cột  
        'lg' => 3,  // Desktop: 3 cột
        'xl' => 4,  // Large: 4 cột
    ];
}
```

## 🎨 Tùy chỉnh giao diện

### CSS Classes có sẵn
- `.fi-wi-stats-overview-stat`: Stats widgets
- `.fi-wi-chart`: Chart widgets
- `.loading`: Loading animation

### Thêm custom CSS
Trong `resources/views/filament/admin/pages/dashboard.blade.php`:
```css
@push('styles')
<style>
    /* Your custom styles */
</style>
@endpush
```

## 🔧 Troubleshooting

### Widget không refresh
1. Kiểm tra `pollingInterval` đã được set
2. Kiểm tra JavaScript console có lỗi không
3. Đảm bảo Livewire hoạt động bình thường

### Dữ liệu không chính xác
1. Kiểm tra relationships trong models
2. Kiểm tra query trong widget methods
3. Clear cache: `php artisan cache:clear`

### Performance issues
1. Giảm frequency refresh
2. Optimize database queries
3. Sử dụng database indexing
4. Cache kết quả nếu cần

## 📱 Mobile Support
Dashboard đã được tối ưu cho mobile:
- Responsive grid layout
- Touch-friendly interactions
- Optimized chart sizes
- Mobile-first CSS

## 🔮 Tính năng nâng cao (có thể mở rộng)

### Broadcasting với Laravel Echo
- Cài đặt Laravel Echo + Pusher/Socket.io
- Real-time updates không cần polling
- Instant notifications

### Export dữ liệu
- Export charts thành PDF/PNG
- Export data thành Excel/CSV

### Alerts & Notifications
- Cảnh báo khi có đơn hàng mới
- Thông báo khi sản phẩm hết hàng
- Email reports tự động

---

**Lưu ý**: Dashboard này được thiết kế để hoạt động mượt mà với PHP 8.1+ và không cần các package phức tạp như Laravel Trend.
