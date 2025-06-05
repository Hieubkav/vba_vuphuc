<?php

/**
 * Script áp dụng OptimizedFilamentResource cho 4 Resources cuối cùng
 */

echo "🚀 Áp dụng optimization cho 4 Resources cuối cùng...\n\n";

$resources = [
    'CourseMaterialResource.php',
    'MenuItemResource.php',
    'PostCategoryResource.php', 
    'UserResource.php',
];

$resourcesPath = 'app/Filament/Admin/Resources/';
$optimizedCount = 0;
$errorCount = 0;

foreach ($resources as $resourceFile) {
    $filePath = $resourcesPath . $resourceFile;
    
    echo "📝 Đang tối ưu: {$resourceFile}...\n";
    
    if (!file_exists($filePath)) {
        echo "   ⚠️ File không tồn tại\n";
        continue;
    }
    
    try {
        $content = file_get_contents($filePath);
        
        // Kiểm tra xem đã có OptimizedFilamentResource trait chưa
        if (strpos($content, 'OptimizedFilamentResource') !== false) {
            echo "   ✅ Đã có optimization\n";
            continue;
        }
        
        // 1. Thêm use statement
        if (strpos($content, 'use App\Traits\OptimizedFilamentResource;') === false) {
            // Tìm vị trí thêm use statement (sau các use khác)
            $usePattern = '/^use\s+[^;]+;$/m';
            preg_match_all($usePattern, $content, $matches, PREG_OFFSET_CAPTURE);
            
            if (!empty($matches[0])) {
                $lastUse = end($matches[0]);
                $insertPos = $lastUse[1] + strlen($lastUse[0]);
                $content = substr_replace($content, "\nuse App\Traits\OptimizedFilamentResource;", $insertPos, 0);
            }
        }
        
        // 2. Thêm trait vào class
        $classPattern = '/class\s+\w+\s+extends\s+Resource\s*\{/';
        if (preg_match($classPattern, $content, $matches, PREG_OFFSET_CAPTURE)) {
            $classPos = $matches[0][1] + strlen($matches[0][0]);
            $content = substr_replace($content, "\n    use OptimizedFilamentResource;\n", $classPos, 0);
        }
        
        // Ghi lại file
        file_put_contents($filePath, $content);
        
        // Kiểm tra syntax
        $output = [];
        $returnCode = 0;
        exec("php -l {$filePath} 2>&1", $output, $returnCode);
        
        if ($returnCode === 0) {
            echo "   ✅ Đã áp dụng optimization thành công\n";
            $optimizedCount++;
        } else {
            echo "   ❌ Lỗi syntax: " . implode(' ', $output) . "\n";
            $errorCount++;
        }
        
    } catch (Exception $e) {
        echo "   ❌ Lỗi: " . $e->getMessage() . "\n";
        $errorCount++;
    }
}

echo "\n📊 Kết quả:\n";
echo "✅ Đã tối ưu: {$optimizedCount} resources\n";
echo "❌ Lỗi: {$errorCount} resources\n";

// Kiểm tra syntax tất cả Resources
echo "\n🔍 Kiểm tra syntax tất cả Resources...\n";
$allResources = glob($resourcesPath . '*.php');
$allOk = true;

foreach ($allResources as $file) {
    $fileName = basename($file);
    
    exec("php -l {$file} 2>&1", $output, $returnCode);
    
    if ($returnCode === 0) {
        echo "   ✅ {$fileName}\n";
    } else {
        echo "   ❌ {$fileName}\n";
        $allOk = false;
    }
}

if ($allOk) {
    echo "\n🎉 Tất cả Resources đều OK!\n";
    echo "\n📖 Các bước tiếp theo:\n";
    echo "1. Chạy: php scripts/check-filament-optimization-status.php\n";
    echo "2. Test admin panel\n";
    echo "3. Chạy: php artisan filament:optimize --stats\n";
} else {
    echo "\n⚠️ Vẫn còn một số Resources có lỗi\n";
}

echo "\n✨ Script hoàn thành!\n";
