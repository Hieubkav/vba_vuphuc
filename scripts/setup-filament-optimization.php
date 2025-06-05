<?php

/**
 * Script setup tự động cho Filament Optimization
 * 
 * Chạy script này để tự động cài đặt và cấu hình
 * tối ưu hóa Filament cho dự án
 */

echo "🚀 Bắt đầu setup Filament Optimization...\n\n";

// 1. Kiểm tra môi trường
echo "1️⃣ Kiểm tra môi trường...\n";

if (!file_exists('artisan')) {
    die("❌ Không tìm thấy file artisan. Vui lòng chạy script từ thư mục gốc Laravel.\n");
}

if (!class_exists('Filament\Facades\Filament')) {
    echo "⚠️ Filament chưa được cài đặt. Đang cài đặt...\n";
    exec('composer require filament/filament:"^3.0"');
}

echo "✅ Môi trường OK\n\n";

// 2. Tạo các file cần thiết
echo "2️⃣ Tạo các file tối ưu hóa...\n";

$files = [
    'app/Services/FilamentOptimizationService.php',
    'app/Traits/OptimizedFilamentResource.php',
    'app/Console/Commands/OptimizeFilamentCommand.php',
    'app/Providers/FilamentOptimizationServiceProvider.php',
    'app/Http/Middleware/FilamentOptimizationMiddleware.php',
    'config/filament-optimization.php',
];

foreach ($files as $file) {
    if (file_exists($file)) {
        echo "✅ {$file} đã tồn tại\n";
    } else {
        echo "❌ {$file} chưa tồn tại\n";
    }
}

echo "\n";

// 3. Cập nhật config
echo "3️⃣ Cập nhật cấu hình...\n";

// Cập nhật .env
$envFile = '.env';
if (file_exists($envFile)) {
    $envContent = file_get_contents($envFile);
    
    $optimizationSettings = "
# Filament Optimization Settings
FILAMENT_QUERY_CACHE=true
FILAMENT_CACHE_DURATION=300
FILAMENT_EAGER_LOADING=true
FILAMENT_MEMORY_OPTIMIZATION=true
FILAMENT_ASSET_OPTIMIZATION=true
FILAMENT_PAGINATION_SIZE=25
FILAMENT_CACHE_STORE=redis
FILAMENT_MEMORY_LIMIT=256M
FILAMENT_LOG_SLOW_QUERIES=true
FILAMENT_MAX_QUERY_TIME=1000
FILAMENT_PERFORMANCE_LOGGING=true
";

    if (!str_contains($envContent, 'FILAMENT_QUERY_CACHE')) {
        file_put_contents($envFile, $envContent . $optimizationSettings);
        echo "✅ Đã cập nhật .env\n";
    } else {
        echo "✅ .env đã có cấu hình optimization\n";
    }
} else {
    echo "⚠️ Không tìm thấy file .env\n";
}

// 4. Chạy các lệnh cần thiết
echo "\n4️⃣ Chạy các lệnh setup...\n";

$commands = [
    'php artisan config:clear',
    'php artisan cache:clear',
    'php artisan route:clear',
    'php artisan view:clear',
];

foreach ($commands as $command) {
    echo "Chạy: {$command}\n";
    exec($command, $output, $returnCode);
    
    if ($returnCode === 0) {
        echo "✅ Thành công\n";
    } else {
        echo "❌ Lỗi: " . implode("\n", $output) . "\n";
    }
}

// 5. Test optimization
echo "\n5️⃣ Test tối ưu hóa...\n";

if (file_exists('app/Console/Commands/OptimizeFilamentCommand.php')) {
    echo "Chạy: php artisan filament:optimize --stats\n";
    exec('php artisan filament:optimize --stats', $output, $returnCode);
    
    if ($returnCode === 0) {
        echo "✅ Command optimization hoạt động\n";
        echo implode("\n", $output) . "\n";
    } else {
        echo "❌ Command optimization có lỗi\n";
    }
} else {
    echo "⚠️ OptimizeFilamentCommand chưa được tạo\n";
}

// 6. Hướng dẫn sử dụng
echo "\n6️⃣ Hướng dẫn sử dụng:\n";
echo "
📖 Để sử dụng optimization trong Resource:

1. Thêm trait vào Resource:
   use App\Traits\OptimizedFilamentResource;

2. Override các method cần thiết:
   protected static function getTableColumns(): array
   {
       return ['id', 'name', 'status', 'created_at'];
   }

3. Chạy optimization:
   php artisan filament:optimize

📚 Xem thêm tài liệu tại: docs/filament-optimization-guide.md
";

// 7. Kiểm tra Redis (nếu sử dụng)
echo "\n7️⃣ Kiểm tra Redis...\n";

try {
    if (extension_loaded('redis')) {
        $redis = new Redis();
        $redis->connect('127.0.0.1', 6379);
        $redis->ping();
        echo "✅ Redis hoạt động tốt\n";
        $redis->close();
    } else {
        echo "⚠️ Redis extension chưa được cài đặt\n";
        echo "   Cài đặt: sudo apt-get install php-redis (Ubuntu)\n";
        echo "   Hoặc sử dụng file cache: CACHE_DRIVER=file\n";
    }
} catch (Exception $e) {
    echo "⚠️ Redis không hoạt động: " . $e->getMessage() . "\n";
    echo "   Khởi động Redis: sudo service redis-server start\n";
    echo "   Hoặc sử dụng file cache: CACHE_DRIVER=file\n";
}

// 8. Tạo backup config
echo "\n8️⃣ Tạo backup cấu hình...\n";

$backupDir = 'storage/backups/filament-optimization';
if (!is_dir($backupDir)) {
    mkdir($backupDir, 0755, true);
}

$configFiles = [
    'config/filament.php',
    'config/filament-optimization.php',
    '.env',
];

foreach ($configFiles as $file) {
    if (file_exists($file)) {
        $backupFile = $backupDir . '/' . basename($file) . '.' . date('Y-m-d-H-i-s') . '.backup';
        copy($file, $backupFile);
        echo "✅ Backup: {$file} -> {$backupFile}\n";
    }
}

echo "\n🎉 Setup hoàn thành!\n";
echo "
📊 Các lệnh hữu ích:

# Tối ưu toàn diện
php artisan filament:optimize

# Xem thống kê hiệu suất
php artisan filament:optimize --stats

# Phân tích vấn đề
php artisan filament:optimize --analyze

# Xóa cache
php artisan filament:optimize --clear-cache

# Tự động sửa lỗi
php artisan filament:optimize --fix

🚀 Hãy test admin panel để thấy sự khác biệt về tốc độ!
";
