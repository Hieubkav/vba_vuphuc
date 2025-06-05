<?php

/**
 * Script áp dụng OptimizedFilamentResource cho 6 Resources còn lại
 */

echo "🚀 Áp dụng optimization cho 6 Resources còn lại...\n\n";

// Danh sách 6 Resources chưa tối ưu
$resources = [
    'CourseGroupResource.php' => [
        'table_columns' => ['id', 'name', 'description', 'platform', 'status', 'order'],
        'searchable' => ['name', 'description'],
        'relationships' => [],
    ],
    'CourseMaterialResource.php' => [
        'table_columns' => ['id', 'title', 'type', 'status', 'course_id', 'order'],
        'searchable' => ['title'],
        'relationships' => ['course:id,title'],
    ],
    'FaqResource.php' => [
        'table_columns' => ['id', 'question', 'answer', 'status', 'order'],
        'searchable' => ['question', 'answer'],
        'relationships' => [],
    ],
    'MenuItemResource.php' => [
        'table_columns' => ['id', 'label', 'url', 'parent_id', 'status', 'order'],
        'searchable' => ['label', 'url'],
        'relationships' => ['parent:id,label', 'children:id,label'],
    ],
    'PostCategoryResource.php' => [
        'table_columns' => ['id', 'name', 'slug', 'status', 'order'],
        'searchable' => ['name', 'slug'],
        'relationships' => ['posts:id,title'],
    ],
    'UserResource.php' => [
        'table_columns' => ['id', 'name', 'email', 'email_verified_at', 'created_at'],
        'searchable' => ['name', 'email'],
        'relationships' => [],
    ],
];

$resourcesPath = 'app/Filament/Admin/Resources/';
$optimizedCount = 0;
$errorCount = 0;

foreach ($resources as $resourceFile => $config) {
    $filePath = $resourcesPath . $resourceFile;
    
    echo "📝 Đang tối ưu: {$resourceFile}...\n";
    
    if (!file_exists($filePath)) {
        echo "   ⚠️ File không tồn tại: {$filePath}\n";
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
        
        // 3. Thêm các method optimization ở cuối class (trước dấu } cuối)
        $optimizationMethods = generateOptimizationMethods($config);
        $content = preg_replace('/}\s*$/', $optimizationMethods . "\n}", $content);
        
        // Ghi lại file
        file_put_contents($filePath, $content);
        
        echo "   ✅ Đã áp dụng optimization\n";
        $optimizedCount++;
        
    } catch (Exception $e) {
        echo "   ❌ Lỗi: " . $e->getMessage() . "\n";
        $errorCount++;
    }
}

echo "\n📊 Kết quả:\n";
echo "✅ Đã tối ưu: {$optimizedCount} resources\n";
echo "❌ Lỗi: {$errorCount} resources\n";

// Test optimization
echo "\n🧪 Test optimization...\n";
testOptimization();

echo "\n🎉 Hoàn thành áp dụng optimization cho tất cả Resources!\n";
echo "\n📖 Các bước tiếp theo:\n";
echo "1. Chạy: php artisan config:clear\n";
echo "2. Chạy: php artisan filament:optimize --stats\n";
echo "3. Test admin panel để kiểm tra hiệu suất\n";

/**
 * Tạo các method optimization cho resource
 */
function generateOptimizationMethods(array $config): string
{
    $tableColumns = var_export($config['table_columns'], true);
    $searchableColumns = var_export($config['searchable'], true);
    
    $relationshipsCode = '';
    if (!empty($config['relationships'])) {
        $relationshipsArray = [];
        foreach ($config['relationships'] as $relation) {
            $relationshipsArray[] = "            '{$relation}'";
        }
        $relationshipsCode = "[\n" . implode(",\n", $relationshipsArray) . "\n        ]";
    } else {
        $relationshipsCode = '[]';
    }
    
    return "
    /**
     * Lấy danh sách cột cần thiết cho table
     */
    protected static function getTableColumns(): array
    {
        return {$tableColumns};
    }

    /**
     * Lấy relationships cần thiết cho form
     */
    protected static function getFormRelationships(): array
    {
        return {$relationshipsCode};
    }

    /**
     * Lấy các cột có thể search
     */
    protected static function getSearchableColumns(): array
    {
        return {$searchableColumns};
    }";
}

/**
 * Test optimization
 */
function testOptimization(): void
{
    $resources = [
        'CourseGroupResource.php',
        'CourseMaterialResource.php',
        'FaqResource.php',
        'MenuItemResource.php',
        'PostCategoryResource.php',
        'UserResource.php',
    ];
    
    $resourcesPath = 'app/Filament/Admin/Resources/';
    
    foreach ($resources as $resourceFile) {
        $filePath = $resourcesPath . $resourceFile;
        
        if (file_exists($filePath)) {
            $content = file_get_contents($filePath);
            
            if (strpos($content, 'OptimizedFilamentResource') !== false) {
                echo "   ✅ {$resourceFile} đã có optimization\n";
            } else {
                echo "   ❌ {$resourceFile} chưa có optimization\n";
            }
        } else {
            echo "   ⚠️ {$resourceFile} không tồn tại\n";
        }
    }
}
