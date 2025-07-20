<?php

/**
 * Test script để kiểm tra cache invalidation cho CourseGroup
 * Chạy script này để test xem cache có được clear đúng cách không
 */

require_once __DIR__ . '/vendor/autoload.php';

use Illuminate\Support\Facades\Cache;
use App\Providers\ViewServiceProvider;

echo "=== Test CourseGroup Cache Invalidation ===\n\n";

// Test 1: Kiểm tra cache key hiện tại
echo "1. Kiểm tra cache keys hiện tại:\n";
$cacheKeys = [
    'storefront_course_groups',
    'course_group_types', 
    'course_group_stats'
];

foreach ($cacheKeys as $key) {
    $exists = Cache::has($key);
    echo "   - {$key}: " . ($exists ? "EXISTS" : "NOT EXISTS") . "\n";
}

echo "\n";

// Test 2: Test clearCourseGroupsCache method
echo "2. Test clearCourseGroupsCache method:\n";
try {
    ViewServiceProvider::clearCourseGroupsCache();
    echo "   ✓ clearCourseGroupsCache() executed successfully\n";
} catch (Exception $e) {
    echo "   ✗ Error: " . $e->getMessage() . "\n";
}

echo "\n";

// Test 3: Test refreshCache('course_groups')
echo "3. Test refreshCache('course_groups'):\n";
try {
    ViewServiceProvider::refreshCache('course_groups');
    echo "   ✓ refreshCache('course_groups') executed successfully\n";
} catch (Exception $e) {
    echo "   ✗ Error: " . $e->getMessage() . "\n";
}

echo "\n";

// Test 4: Test refreshCache('storefront')
echo "4. Test refreshCache('storefront'):\n";
try {
    ViewServiceProvider::refreshCache('storefront');
    echo "   ✓ refreshCache('storefront') executed successfully\n";
} catch (Exception $e) {
    echo "   ✗ Error: " . $e->getMessage() . "\n";
}

echo "\n";

// Test 5: Kiểm tra lại cache keys sau khi clear
echo "5. Kiểm tra cache keys sau khi clear:\n";
foreach ($cacheKeys as $key) {
    $exists = Cache::has($key);
    echo "   - {$key}: " . ($exists ? "EXISTS" : "NOT EXISTS") . "\n";
}

echo "\n=== Test hoàn thành ===\n";
echo "Nếu tất cả test đều PASS, cache invalidation đã hoạt động đúng!\n";
