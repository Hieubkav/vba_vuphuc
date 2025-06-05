<?php

/**
 * Script test hiệu suất Filament sau khi áp dụng optimization
 * 
 * Kiểm tra tốc độ load và memory usage của các Resources
 */

require_once __DIR__ . '/../vendor/autoload.php';

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

echo "🧪 Bắt đầu test hiệu suất Filament...\n\n";

// Khởi tạo Laravel app
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

// Test các Resources
$resources = [
    'PostResource',
    'CourseResource', 
    'AlbumResource',
    'CatCourseResource',
    'UserResource'
];

$results = [];

foreach ($resources as $resourceName) {
    echo "📊 Testing {$resourceName}...\n";
    
    $startTime = microtime(true);
    $startMemory = memory_get_usage(true);
    
    try {
        // Simulate loading resource
        $resourceClass = "App\\Filament\\Admin\\Resources\\{$resourceName}";
        
        if (class_exists($resourceClass)) {
            // Test getEloquentQuery
            if (method_exists($resourceClass, 'getEloquentQuery')) {
                $query = $resourceClass::getEloquentQuery();
                $count = $query->count();
                echo "   📈 Records: {$count}\n";
            }
            
            // Test getTableColumns
            if (method_exists($resourceClass, 'getTableColumns')) {
                $columns = $resourceClass::getTableColumns();
                echo "   📋 Optimized columns: " . count($columns) . "\n";
            }
            
            // Test getFormRelationships
            if (method_exists($resourceClass, 'getFormRelationships')) {
                $relationships = $resourceClass::getFormRelationships();
                echo "   🔗 Optimized relationships: " . count($relationships) . "\n";
            }
            
            // Test navigation badge (cached)
            if (method_exists($resourceClass, 'getNavigationBadge')) {
                $badge = $resourceClass::getNavigationBadge();
                echo "   🏷️ Navigation badge: {$badge}\n";
            }
        }
        
    } catch (Exception $e) {
        echo "   ❌ Error: " . $e->getMessage() . "\n";
    }
    
    $endTime = microtime(true);
    $endMemory = memory_get_usage(true);
    
    $executionTime = ($endTime - $startTime) * 1000; // ms
    $memoryUsed = ($endMemory - $startMemory) / 1024 / 1024; // MB
    
    $results[$resourceName] = [
        'execution_time' => round($executionTime, 2),
        'memory_used' => round($memoryUsed, 2),
    ];
    
    echo "   ⏱️ Time: {$results[$resourceName]['execution_time']}ms\n";
    echo "   🧠 Memory: {$results[$resourceName]['memory_used']}MB\n\n";
}

// Tổng kết
echo "📊 Tổng kết hiệu suất:\n";
echo "+------------------+---------------+-------------+\n";
echo "| Resource         | Time (ms)     | Memory (MB) |\n";
echo "+------------------+---------------+-------------+\n";

$totalTime = 0;
$totalMemory = 0;

foreach ($results as $resource => $stats) {
    $totalTime += $stats['execution_time'];
    $totalMemory += $stats['memory_used'];
    
    printf("| %-16s | %-13s | %-11s |\n", 
        $resource, 
        $stats['execution_time'] . 'ms',
        $stats['memory_used'] . 'MB'
    );
}

echo "+------------------+---------------+-------------+\n";
printf("| %-16s | %-13s | %-11s |\n", 
    'TOTAL', 
    round($totalTime, 2) . 'ms',
    round($totalMemory, 2) . 'MB'
);
echo "+------------------+---------------+-------------+\n\n";

// Đánh giá hiệu suất
echo "🎯 Đánh giá hiệu suất:\n";

$avgTime = $totalTime / count($results);
$avgMemory = $totalMemory / count($results);

if ($avgTime < 100) {
    echo "✅ Tốc độ: XUẤT SẮC (< 100ms/resource)\n";
} elseif ($avgTime < 300) {
    echo "✅ Tốc độ: TỐT (< 300ms/resource)\n";
} elseif ($avgTime < 500) {
    echo "⚠️ Tốc độ: TRUNG BÌNH (< 500ms/resource)\n";
} else {
    echo "❌ Tốc độ: CẦN CẢI THIỆN (> 500ms/resource)\n";
}

if ($avgMemory < 5) {
    echo "✅ Memory: XUẤT SẮC (< 5MB/resource)\n";
} elseif ($avgMemory < 10) {
    echo "✅ Memory: TỐT (< 10MB/resource)\n";
} elseif ($avgMemory < 20) {
    echo "⚠️ Memory: TRUNG BÌNH (< 20MB/resource)\n";
} else {
    echo "❌ Memory: CẦN CẢI THIỆN (> 20MB/resource)\n";
}

echo "\n📈 Khuyến nghị:\n";

if ($avgTime > 200) {
    echo "- Cân nhắc tăng cache duration cho queries chậm\n";
    echo "- Kiểm tra và tối ưu thêm eager loading\n";
}

if ($avgMemory > 10) {
    echo "- Cân nhắc giảm số lượng columns trong getTableColumns()\n";
    echo "- Tối ưu relationships loading\n";
}

if ($avgTime < 100 && $avgMemory < 5) {
    echo "🎉 Hiệu suất đã được tối ưu rất tốt!\n";
    echo "🚀 Admin panel sẽ load nhanh và mượt mà!\n";
}

echo "\n💡 Tips:\n";
echo "- Chạy 'php artisan filament:optimize --stats' để xem cache statistics\n";
echo "- Chạy 'php artisan filament:optimize --analyze' để phân tích vấn đề\n";
echo "- Monitor logs để theo dõi slow queries\n";

echo "\n✅ Test hoàn thành!\n";
