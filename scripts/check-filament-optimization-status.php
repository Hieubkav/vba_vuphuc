<?php

/**
 * Script kiểm tra trạng thái áp dụng FilamentOptimization cho toàn dự án
 */

echo "🔍 Kiểm tra trạng thái FilamentOptimization cho toàn dự án...\n\n";

// 1. Kiểm tra các core files
echo "1️⃣ Kiểm tra Core Files:\n";
$coreFiles = [
    'app/Services/FilamentOptimizationService.php' => 'FilamentOptimizationService',
    'app/Traits/OptimizedFilamentResource.php' => 'OptimizedFilamentResource Trait',
    'app/Http/Middleware/FilamentOptimizationMiddleware.php' => 'FilamentOptimizationMiddleware',
    'app/Providers/FilamentOptimizationServiceProvider.php' => 'FilamentOptimizationServiceProvider',
    'app/Console/Commands/OptimizeFilamentCommand.php' => 'OptimizeFilamentCommand',
    'config/filament-optimization.php' => 'Config file',
];

$coreStatus = [];
foreach ($coreFiles as $file => $name) {
    if (file_exists($file)) {
        echo "   ✅ {$name}\n";
        $coreStatus[$name] = true;
    } else {
        echo "   ❌ {$name} - MISSING\n";
        $coreStatus[$name] = false;
    }
}

// 2. Kiểm tra Service Provider registration
echo "\n2️⃣ Kiểm tra Service Provider Registration:\n";
$appConfig = file_get_contents('config/app.php');
if (strpos($appConfig, 'FilamentOptimizationServiceProvider') !== false) {
    echo "   ✅ FilamentOptimizationServiceProvider đã được đăng ký\n";
    $coreStatus['ServiceProvider Registration'] = true;
} else {
    echo "   ❌ FilamentOptimizationServiceProvider chưa được đăng ký\n";
    $coreStatus['ServiceProvider Registration'] = false;
}

// 3. Kiểm tra Middleware registration
echo "\n3️⃣ Kiểm tra Middleware Registration:\n";
$adminPanelProvider = 'app/Providers/Filament/AdminPanelProvider.php';
if (file_exists($adminPanelProvider)) {
    $content = file_get_contents($adminPanelProvider);
    if (strpos($content, 'FilamentOptimizationMiddleware') !== false) {
        echo "   ✅ FilamentOptimizationMiddleware đã được đăng ký trong AdminPanelProvider\n";
        $coreStatus['Middleware Registration'] = true;
    } else {
        echo "   ❌ FilamentOptimizationMiddleware chưa được đăng ký trong AdminPanelProvider\n";
        $coreStatus['Middleware Registration'] = false;
    }
} else {
    echo "   ⚠️ AdminPanelProvider không tồn tại\n";
    $coreStatus['Middleware Registration'] = false;
}

// 4. Kiểm tra Resources đã sử dụng OptimizedFilamentResource trait
echo "\n4️⃣ Kiểm tra Resources sử dụng OptimizedFilamentResource:\n";
$resourcesPath = 'app/Filament/Admin/Resources/';
$resourceFiles = glob($resourcesPath . '*.php');

$optimizedResources = [];
$notOptimizedResources = [];

foreach ($resourceFiles as $file) {
    $fileName = basename($file);
    $content = file_get_contents($file);
    
    if (strpos($content, 'OptimizedFilamentResource') !== false) {
        echo "   ✅ {$fileName}\n";
        $optimizedResources[] = $fileName;
    } else {
        echo "   ❌ {$fileName} - Chưa sử dụng OptimizedFilamentResource\n";
        $notOptimizedResources[] = $fileName;
    }
}

// 5. Kiểm tra Commands
echo "\n5️⃣ Kiểm tra Commands:\n";
try {
    $output = [];
    $returnCode = 0;
    exec('php artisan list filament 2>&1', $output, $returnCode);
    
    $hasOptimizeCommand = false;
    foreach ($output as $line) {
        if (strpos($line, 'filament:optimize') !== false) {
            $hasOptimizeCommand = true;
            break;
        }
    }
    
    if ($hasOptimizeCommand) {
        echo "   ✅ Command filament:optimize có sẵn\n";
        $coreStatus['Commands'] = true;
    } else {
        echo "   ❌ Command filament:optimize không có sẵn\n";
        $coreStatus['Commands'] = false;
    }
} catch (Exception $e) {
    echo "   ⚠️ Không thể kiểm tra commands: " . $e->getMessage() . "\n";
    $coreStatus['Commands'] = false;
}

// 6. Kiểm tra Environment Variables
echo "\n6️⃣ Kiểm tra Environment Variables:\n";
$envExample = file_get_contents('.env.example');
$filamentVars = [
    'FILAMENT_QUERY_CACHE',
    'FILAMENT_CACHE_DURATION',
    'FILAMENT_EAGER_LOADING',
    'FILAMENT_MEMORY_OPTIMIZATION',
    'FILAMENT_ASSET_OPTIMIZATION',
];

$envStatus = true;
foreach ($filamentVars as $var) {
    if (strpos($envExample, $var) !== false) {
        echo "   ✅ {$var}\n";
    } else {
        echo "   ❌ {$var} - Missing\n";
        $envStatus = false;
    }
}
$coreStatus['Environment Variables'] = $envStatus;

// 7. Tổng kết
echo "\n📊 TỔNG KẾT:\n";
echo "=====================================\n";

$totalCore = count($coreStatus);
$passedCore = count(array_filter($coreStatus));

echo "🔧 Core Components: {$passedCore}/{$totalCore} ✅\n";
echo "📁 Resources tối ưu: " . count($optimizedResources) . "/" . (count($optimizedResources) + count($notOptimizedResources)) . " ✅\n";

if (count($notOptimizedResources) > 0) {
    echo "\n⚠️ Resources chưa tối ưu:\n";
    foreach ($notOptimizedResources as $resource) {
        echo "   - {$resource}\n";
    }
}

// 8. Đánh giá tổng thể
$overallScore = ($passedCore / $totalCore) * 100;
$resourceScore = (count($optimizedResources) / (count($optimizedResources) + count($notOptimizedResources))) * 100;
$totalScore = ($overallScore + $resourceScore) / 2;

echo "\n🎯 ĐÁNH GIÁ TỔNG THỂ:\n";
echo "=====================================\n";
echo "Core Components: " . round($overallScore, 1) . "%\n";
echo "Resources Optimization: " . round($resourceScore, 1) . "%\n";
echo "Tổng điểm: " . round($totalScore, 1) . "%\n";

if ($totalScore >= 90) {
    echo "🎉 XUẤT SẮC - FilamentOptimization đã được áp dụng toàn diện!\n";
} elseif ($totalScore >= 70) {
    echo "✅ TỐT - Đa số đã được tối ưu, cần hoàn thiện một số phần\n";
} elseif ($totalScore >= 50) {
    echo "⚠️ TRUNG BÌNH - Cần áp dụng thêm optimization\n";
} else {
    echo "❌ CẦN CẢI THIỆN - FilamentOptimization chưa được áp dụng đầy đủ\n";
}

// 9. Khuyến nghị
echo "\n💡 KHUYẾN NGHỊ:\n";
echo "=====================================\n";

if (count($notOptimizedResources) > 0) {
    echo "1. Áp dụng OptimizedFilamentResource cho các Resources chưa tối ưu\n";
    echo "   Chạy: php scripts/apply-optimization-to-all-resources.php\n\n";
}

if (!$coreStatus['Commands']) {
    echo "2. Đăng ký OptimizeFilamentCommand\n";
    echo "   Kiểm tra FilamentOptimizationServiceProvider\n\n";
}

if (!$coreStatus['Middleware Registration']) {
    echo "3. Đăng ký FilamentOptimizationMiddleware trong AdminPanelProvider\n\n";
}

echo "4. Test optimization:\n";
echo "   php artisan filament:optimize --stats\n\n";

echo "5. Xem hướng dẫn chi tiết:\n";
echo "   docs/filament-optimization-guide.md\n";

echo "\n✨ Script hoàn thành!\n";
