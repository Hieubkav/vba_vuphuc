<?php

/**
 * Script áp dụng optimization cho tất cả Filament Resources
 * 
 * Tự động thêm OptimizedFilamentResource trait và các method cần thiết
 * cho tất cả Resources trong dự án
 */

echo "🚀 Bắt đầu áp dụng optimization cho tất cả Filament Resources...\n\n";

// Danh sách các Resources cần tối ưu
$resources = [
    'AlbumImageResource' => [
        'table_columns' => ['id', 'album_id', 'image_path', 'alt_text', 'caption', 'order', 'status', 'created_at'],
        'relationships' => ['album' => 'select:id,title'],
        'searchable' => ['alt_text', 'caption'],
    ],
    'AssociationResource' => [
        'table_columns' => ['id', 'name', 'description', 'logo_link', 'website_link', 'order', 'status', 'created_at'],
        'relationships' => [],
        'searchable' => ['name', 'description'],
    ],
    'CatCourseResource' => [
        'table_columns' => ['id', 'name', 'slug', 'description', 'status', 'order', 'created_at'],
        'relationships' => ['courses' => 'select:id,title,cat_course_id'],
        'searchable' => ['name', 'description'],
    ],
    'CourseGroupResource' => [
        'table_columns' => ['id', 'name', 'description', 'group_link', 'group_type', 'order', 'status', 'created_at'],
        'relationships' => [],
        'searchable' => ['name', 'description'],
    ],
    'CourseMaterialResource' => [
        'table_columns' => ['id', 'course_id', 'title', 'file_path', 'file_type', 'access_type', 'order', 'created_at'],
        'relationships' => ['course' => 'select:id,title'],
        'searchable' => ['title'],
    ],
    'FaqResource' => [
        'table_columns' => ['id', 'question', 'answer', 'order', 'status', 'created_at'],
        'relationships' => [],
        'searchable' => ['question', 'answer'],
    ],
    'InstructorResource' => [
        'table_columns' => ['id', 'name', 'email', 'specialization', 'bio', 'status', 'created_at'],
        'relationships' => ['courses' => 'select:id,title,instructor_id'],
        'searchable' => ['name', 'email', 'specialization'],
    ],
    'MenuItemResource' => [
        'table_columns' => ['id', 'parent_id', 'label', 'type', 'url', 'order', 'status', 'created_at'],
        'relationships' => ['parent' => 'select:id,label'],
        'searchable' => ['label'],
    ],
    'PartnerResource' => [
        'table_columns' => ['id', 'name', 'logo_link', 'website_link', 'description', 'order', 'status', 'created_at'],
        'relationships' => [],
        'searchable' => ['name', 'description'],
    ],
    'PostCategoryResource' => [
        'table_columns' => ['id', 'name', 'slug', 'description', 'status', 'order', 'created_at'],
        'relationships' => ['posts' => 'select:id,title,category_id'],
        'searchable' => ['name', 'description'],
    ],
    'SliderResource' => [
        'table_columns' => ['id', 'title', 'image_link', 'link', 'description', 'order', 'status', 'created_at'],
        'relationships' => [],
        'searchable' => ['title', 'description'],
    ],
    'StudentResource' => [
        'table_columns' => ['id', 'name', 'email', 'phone', 'status', 'created_at'],
        'relationships' => ['courses' => 'select:id,title'],
        'searchable' => ['name', 'email', 'phone'],
    ],
    'TestimonialResource' => [
        'table_columns' => ['id', 'name', 'position', 'company', 'content', 'rating', 'order', 'status', 'created_at'],
        'relationships' => [],
        'searchable' => ['name', 'position', 'company', 'content'],
    ],
    'UserResource' => [
        'table_columns' => ['id', 'name', 'email', 'email_verified_at', 'created_at'],
        'relationships' => ['roles' => 'select:id,name'],
        'searchable' => ['name', 'email'],
    ],
];

$resourcesPath = 'app/Filament/Admin/Resources/';
$optimizedCount = 0;
$errorCount = 0;

foreach ($resources as $resourceName => $config) {
    $filePath = $resourcesPath . $resourceName . '.php';
    
    echo "📝 Đang xử lý: {$resourceName}...\n";
    
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
        
        // Thêm use statement
        if (strpos($content, 'use App\Traits\OptimizedFilamentResource;') === false) {
            $content = str_replace(
                'use App\Traits\HasImageUpload;',
                "use App\Traits\HasImageUpload;\nuse App\Traits\OptimizedFilamentResource;",
                $content
            );
        }
        
        // Thêm trait vào class
        $content = preg_replace(
            '/use HasImageUpload;/',
            'use HasImageUpload, OptimizedFilamentResource;',
            $content
        );
        
        // Thêm navigation badge với cache
        if (strpos($content, 'getNavigationBadge') === false) {
            $badgeMethod = "
    public static function getNavigationBadge(): ?string
    {
        \$optimizationService = app(\\App\\Services\\FilamentOptimizationService::class);
        
        return \$optimizationService->cacheQuery(
            '{$resourceName}_count_badge',
            function() {
                return static::getModel()::where('status', 'active')->count();
            },
            300 // Cache 5 phút
        );
    }";
            
            // Thêm trước dấu } cuối cùng
            $content = preg_replace('/}\s*$/', $badgeMethod . "\n}", $content);
        }
        
        // Thêm các method optimization
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

// Tối ưu AdminPanelProvider
echo "\n🔧 Đang tối ưu AdminPanelProvider...\n";
optimizeAdminPanelProvider();

echo "\n🎉 Hoàn thành áp dụng optimization cho tất cả Resources!\n";
echo "\n📖 Các bước tiếp theo:\n";
echo "1. Chạy: php artisan config:clear\n";
echo "2. Chạy: php artisan filament:optimize\n";
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
        foreach ($config['relationships'] as $relation => $constraint) {
            if (is_string($constraint) && strpos($constraint, 'select:') === 0) {
                $columns = str_replace('select:', '', $constraint);
                $relationshipsArray[] = "            '{$relation}' => function(\$query) {\n                \$query->select(['{$columns}']);\n            }";
            } else {
                $relationshipsArray[] = "            '{$relation}'";
            }
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
 * Tối ưu AdminPanelProvider
 */
function optimizeAdminPanelProvider(): void
{
    $providerPath = 'app/Providers/Filament/AdminPanelProvider.php';
    
    if (!file_exists($providerPath)) {
        echo "   ⚠️ AdminPanelProvider không tồn tại\n";
        return;
    }
    
    $content = file_get_contents($providerPath);
    
    // Thêm middleware optimization
    if (strpos($content, 'FilamentOptimizationMiddleware') === false) {
        $content = str_replace(
            'DispatchServingFilamentEvent::class,',
            "DispatchServingFilamentEvent::class,\n                \\App\\Http\\Middleware\\FilamentOptimizationMiddleware::class,",
            $content
        );
        
        file_put_contents($providerPath, $content);
        echo "   ✅ Đã thêm optimization middleware\n";
    } else {
        echo "   ✅ AdminPanelProvider đã có optimization\n";
    }
}
