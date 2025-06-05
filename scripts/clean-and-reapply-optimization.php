<?php

/**
 * Script clean và reapply optimization cho 4 Resources có vấn đề
 */

echo "🧹 Clean và reapply optimization cho Resources có vấn đề...\n\n";

$problematicResources = [
    'CourseMaterialResource.php',
    'MenuItemResource.php', 
    'PostCategoryResource.php',
    'UserResource.php',
];

$resourcesPath = 'app/Filament/Admin/Resources/';

foreach ($problematicResources as $resourceFile) {
    $filePath = $resourcesPath . $resourceFile;
    
    echo "🧹 Đang clean: {$resourceFile}...\n";
    
    if (!file_exists($filePath)) {
        echo "   ⚠️ File không tồn tại\n";
        continue;
    }
    
    try {
        $content = file_get_contents($filePath);
        
        // 1. Xóa bỏ use OptimizedFilamentResource
        $content = str_replace("use App\Traits\OptimizedFilamentResource;\n", "", $content);
        $content = str_replace("use OptimizedFilamentResource;\n", "", $content);
        $content = str_replace(", OptimizedFilamentResource", "", $content);
        $content = str_replace("OptimizedFilamentResource, ", "", $content);
        $content = str_replace("OptimizedFilamentResource", "", $content);
        
        // 2. Xóa bỏ tất cả optimization methods
        $methodsToRemove = [
            'getTableColumns',
            'getFormRelationships', 
            'getSearchableColumns'
        ];
        
        foreach ($methodsToRemove as $method) {
            // Xóa bỏ tất cả instances của method này
            $pattern = '/\/\*\*[^*]*\*+(?:[^*\/][^*]*\*+)*\/\s*protected static function ' . $method . '\(\): array\s*\{[^}]*\}/s';
            $content = preg_replace($pattern, '', $content);
            
            // Xóa bỏ method không có comment
            $pattern = '/protected static function ' . $method . '\(\): array\s*\{[^}]*\}/s';
            $content = preg_replace($pattern, '', $content);
        }
        
        // 3. Clean up extra whitespace
        $content = preg_replace('/\n\s*\n\s*\n+/', "\n\n", $content);
        $content = preg_replace('/\}\s*\n\s*\n\s*\}/', "}\n}", $content);
        
        // 4. Đảm bảo file kết thúc đúng cách
        $content = rtrim($content) . "\n";
        
        file_put_contents($filePath, $content);
        
        // Kiểm tra syntax
        $output = [];
        $returnCode = 0;
        exec("php -l {$filePath} 2>&1", $output, $returnCode);
        
        if ($returnCode === 0) {
            echo "   ✅ Đã clean thành công\n";
        } else {
            echo "   ❌ Vẫn còn lỗi sau khi clean: " . implode(' ', $output) . "\n";
        }
        
    } catch (Exception $e) {
        echo "   ❌ Lỗi: " . $e->getMessage() . "\n";
    }
}

echo "\n🔄 Reapply optimization...\n";

// Reapply optimization đơn giản
foreach ($problematicResources as $resourceFile) {
    $filePath = $resourcesPath . $resourceFile;
    
    echo "🔄 Reapply: {$resourceFile}...\n";
    
    if (!file_exists($filePath)) {
        continue;
    }
    
    try {
        $content = file_get_contents($filePath);
        
        // Chỉ thêm use statement và trait, không thêm methods
        if (strpos($content, 'OptimizedFilamentResource') === false) {
            // Thêm use statement
            $usePattern = '/^use\s+[^;]+;$/m';
            preg_match_all($usePattern, $content, $matches, PREG_OFFSET_CAPTURE);
            
            if (!empty($matches[0])) {
                $lastUse = end($matches[0]);
                $insertPos = $lastUse[1] + strlen($lastUse[0]);
                $content = substr_replace($content, "\nuse App\Traits\OptimizedFilamentResource;", $insertPos, 0);
            }
            
            // Thêm trait vào class
            $classPattern = '/class\s+\w+\s+extends\s+Resource\s*\{/';
            if (preg_match($classPattern, $content, $matches, PREG_OFFSET_CAPTURE)) {
                $classPos = $matches[0][1] + strlen($matches[0][0]);
                $content = substr_replace($content, "\n    use OptimizedFilamentResource;\n", $classPos, 0);
            }
            
            file_put_contents($filePath, $content);
            
            // Kiểm tra syntax
            exec("php -l {$filePath} 2>&1", $output, $returnCode);
            
            if ($returnCode === 0) {
                echo "   ✅ Đã reapply thành công\n";
            } else {
                echo "   ❌ Lỗi sau khi reapply: " . implode(' ', $output) . "\n";
            }
        } else {
            echo "   ✅ Đã có optimization\n";
        }
        
    } catch (Exception $e) {
        echo "   ❌ Lỗi: " . $e->getMessage() . "\n";
    }
}

echo "\n📊 Kiểm tra cuối cùng...\n";

// Kiểm tra syntax tất cả Resources
$allResources = glob($resourcesPath . '*.php');
$errorCount = 0;

foreach ($allResources as $file) {
    $fileName = basename($file);
    
    exec("php -l {$file} 2>&1", $output, $returnCode);
    
    if ($returnCode === 0) {
        echo "   ✅ {$fileName}\n";
    } else {
        echo "   ❌ {$fileName}\n";
        $errorCount++;
    }
}

if ($errorCount === 0) {
    echo "\n🎉 Tất cả Resources đều OK!\n";
    echo "\n📖 Các bước tiếp theo:\n";
    echo "1. Chạy: php artisan config:clear\n";
    echo "2. Chạy: php scripts/check-filament-optimization-status.php\n";
    echo "3. Test admin panel\n";
} else {
    echo "\n⚠️ Vẫn còn {$errorCount} Resources có lỗi\n";
}

echo "\n✨ Script hoàn thành!\n";
