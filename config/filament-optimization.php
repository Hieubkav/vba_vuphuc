<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Filament Optimization Configuration
    |--------------------------------------------------------------------------
    |
    | Cấu hình tối ưu hóa cho Filament Admin Panel
    | Các setting này giúp cải thiện hiệu suất mà không gây lỗi
    |
    */

    /*
    |--------------------------------------------------------------------------
    | Query Optimization
    |--------------------------------------------------------------------------
    |
    | Tối ưu hóa database queries
    |
    */
    'query' => [
        'enable_caching' => env('FILAMENT_QUERY_CACHE', true),
        'cache_duration' => env('FILAMENT_CACHE_DURATION', 300), // 5 minutes
        'enable_eager_loading' => env('FILAMENT_EAGER_LOADING', true),
        'max_query_time' => env('FILAMENT_MAX_QUERY_TIME', 1000), // 1 second
        'log_slow_queries' => env('FILAMENT_LOG_SLOW_QUERIES', true),
    ],

    /*
    |--------------------------------------------------------------------------
    | Memory Optimization
    |--------------------------------------------------------------------------
    |
    | Tối ưu hóa memory usage
    |
    */
    'memory' => [
        'enable_optimization' => env('FILAMENT_MEMORY_OPTIMIZATION', true),
        'memory_limit' => env('FILAMENT_MEMORY_LIMIT', '256M'),
        'enable_garbage_collection' => env('FILAMENT_ENABLE_GC', true),
        'gc_probability' => env('FILAMENT_GC_PROBABILITY', 10), // 10%
    ],

    /*
    |--------------------------------------------------------------------------
    | Asset Optimization
    |--------------------------------------------------------------------------
    |
    | Tối ưu hóa CSS/JS assets
    |
    */
    'assets' => [
        'enable_optimization' => env('FILAMENT_ASSET_OPTIMIZATION', true),
        'enable_compression' => env('FILAMENT_ASSET_COMPRESSION', true),
        'cache_duration' => env('FILAMENT_ASSET_CACHE_DURATION', 31536000), // 1 year
        'enable_preloading' => env('FILAMENT_ASSET_PRELOADING', true),
    ],

    /*
    |--------------------------------------------------------------------------
    | Table Optimization
    |--------------------------------------------------------------------------
    |
    | Tối ưu hóa Filament tables
    |
    */
    'table' => [
        'default_pagination' => env('FILAMENT_DEFAULT_PAGINATION', 25),
        'max_pagination' => env('FILAMENT_MAX_PAGINATION', 100),
        'enable_search_optimization' => env('FILAMENT_SEARCH_OPTIMIZATION', true),
        'search_debounce' => env('FILAMENT_SEARCH_DEBOUNCE', 300), // 300ms
    ],

    /*
    |--------------------------------------------------------------------------
    | Form Optimization
    |--------------------------------------------------------------------------
    |
    | Tối ưu hóa Filament forms
    |
    */
    'form' => [
        'enable_lazy_loading' => env('FILAMENT_FORM_LAZY_LOADING', true),
        'max_select_options' => env('FILAMENT_MAX_SELECT_OPTIONS', 1000),
        'enable_select_caching' => env('FILAMENT_SELECT_CACHING', true),
    ],

    /*
    |--------------------------------------------------------------------------
    | Cache Configuration
    |--------------------------------------------------------------------------
    |
    | Cấu hình cache cho optimization
    |
    */
    'cache' => [
        'store' => env('FILAMENT_CACHE_STORE', 'redis'),
        'prefix' => env('FILAMENT_CACHE_PREFIX', 'filament_opt'),
        'tags' => [
            'resources' => 'filament_resources',
            'queries' => 'filament_queries',
            'forms' => 'filament_forms',
            'tables' => 'filament_tables',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Performance Monitoring
    |--------------------------------------------------------------------------
    |
    | Giám sát hiệu suất
    |
    */
    'monitoring' => [
        'enable_logging' => env('FILAMENT_PERFORMANCE_LOGGING', true),
        'log_channel' => env('FILAMENT_LOG_CHANNEL', 'daily'),
        'slow_request_threshold' => env('FILAMENT_SLOW_REQUEST_THRESHOLD', 1000), // 1 second
        'high_memory_threshold' => env('FILAMENT_HIGH_MEMORY_THRESHOLD', 100), // 100MB
    ],

    /*
    |--------------------------------------------------------------------------
    | Resource Specific Optimizations
    |--------------------------------------------------------------------------
    |
    | Tối ưu hóa cho từng resource cụ thể
    |
    */
    'resources' => [
        'posts' => [
            'table_columns' => ['id', 'title', 'status', 'category_id', 'created_at'],
            'eager_load' => ['category', 'images'],
            'cache_duration' => 600, // 10 minutes
        ],
        'courses' => [
            'table_columns' => ['id', 'title', 'status', 'category_id', 'instructor_id', 'created_at'],
            'eager_load' => ['category', 'instructor', 'images'],
            'cache_duration' => 600,
        ],
        'users' => [
            'table_columns' => ['id', 'name', 'email', 'status', 'created_at'],
            'eager_load' => ['roles'],
            'cache_duration' => 300,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Database Optimization
    |--------------------------------------------------------------------------
    |
    | Tối ưu hóa database
    |
    */
    'database' => [
        'enable_query_optimization' => env('FILAMENT_DB_OPTIMIZATION', true),
        'connection_timeout' => env('FILAMENT_DB_TIMEOUT', 30),
        'query_timeout' => env('FILAMENT_DB_QUERY_TIMEOUT', 15),
        'enable_query_log' => env('FILAMENT_DB_QUERY_LOG', false),
    ],

    /*
    |--------------------------------------------------------------------------
    | Security Optimizations
    |--------------------------------------------------------------------------
    |
    | Tối ưu hóa bảo mật
    |
    */
    'security' => [
        'enable_rate_limiting' => env('FILAMENT_RATE_LIMITING', true),
        'rate_limit_requests' => env('FILAMENT_RATE_LIMIT_REQUESTS', 60),
        'rate_limit_minutes' => env('FILAMENT_RATE_LIMIT_MINUTES', 1),
        'enable_csrf_protection' => env('FILAMENT_CSRF_PROTECTION', true),
    ],

    /*
    |--------------------------------------------------------------------------
    | Development Optimizations
    |--------------------------------------------------------------------------
    |
    | Tối ưu hóa cho môi trường development
    |
    */
    'development' => [
        'enable_debug_bar' => env('FILAMENT_DEBUG_BAR', false),
        'enable_query_debugging' => env('FILAMENT_QUERY_DEBUG', false),
        'enable_performance_profiling' => env('FILAMENT_PERFORMANCE_PROFILING', false),
    ],
];
