<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Services\FilamentOptimizationService;
use Illuminate\Support\Facades\Log;

/**
 * Middleware tối ưu hóa cho Filament Admin Panel
 * 
 * Áp dụng các tối ưu hóa hiệu suất cho requests đến Filament
 */
class FilamentOptimizationMiddleware
{
    protected FilamentOptimizationService $optimizationService;

    public function __construct(FilamentOptimizationService $optimizationService)
    {
        $this->optimizationService = $optimizationService;
    }

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Chỉ áp dụng cho Filament admin routes
        if (!$this->isFilamentRequest($request)) {
            return $next($request);
        }

        try {
            $startTime = microtime(true);
            $startMemory = memory_get_usage(true);

            // Tối ưu hóa trước khi xử lý request
            $this->beforeRequest($request);

            $response = $next($request);

            // Tối ưu hóa sau khi xử lý request
            $this->afterRequest($request, $response, $startTime, $startMemory);

            return $response;
        } catch (\Exception $e) {
            // Log lỗi và tiếp tục xử lý request bình thường
            Log::error('FilamentOptimizationMiddleware error', [
                'url' => $request->fullUrl(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            // Trả về response bình thường nếu có lỗi
            return $next($request);
        }
    }

    /**
     * Kiểm tra xem có phải request đến Filament không
     */
    protected function isFilamentRequest(Request $request): bool
    {
        return $request->is('admin/*') || 
               $request->is('filament/*') ||
               str_contains($request->path(), 'admin');
    }

    /**
     * Tối ưu hóa trước khi xử lý request
     */
    protected function beforeRequest(Request $request): void
    {
        // Tối ưu memory
        if (config('filament.optimization.enable_memory_optimization', true)) {
            $this->optimizeMemoryBefore();
        }

        // Set timeout cho database queries
        $this->setQueryTimeout();

        // Tối ưu session cho admin
        $this->optimizeSession($request);
    }

    /**
     * Tối ưu hóa sau khi xử lý request
     */
    protected function afterRequest(Request $request, Response $response, float $startTime, int $startMemory): void
    {
        $executionTime = (microtime(true) - $startTime) * 1000; // ms
        $memoryUsed = memory_get_usage(true) - $startMemory;

        // Log performance metrics
        $this->logPerformanceMetrics($request, $executionTime, $memoryUsed);

        // Tối ưu memory sau request
        if (config('filament.optimization.enable_memory_optimization', true)) {
            $this->optimizeMemoryAfter();
        }

        // Thêm headers tối ưu hóa
        $this->addOptimizationHeaders($response);

        // Clear cache nếu cần
        $this->conditionalCacheClear($request);
    }

    /**
     * Tối ưu memory trước request
     */
    protected function optimizeMemoryBefore(): void
    {
        // Increase memory limit for admin operations
        if (ini_get('memory_limit') !== '-1') {
            $currentLimit = $this->parseMemoryLimit(ini_get('memory_limit'));
            $recommendedLimit = 256 * 1024 * 1024; // 256MB

            if ($currentLimit < $recommendedLimit) {
                ini_set('memory_limit', '256M');
            }
        }

        // Force garbage collection
        if (function_exists('gc_collect_cycles')) {
            gc_collect_cycles();
        }
    }

    /**
     * Tối ưu memory sau request
     */
    protected function optimizeMemoryAfter(): void
    {
        $this->optimizationService->optimizeMemory();
    }

    /**
     * Set timeout cho database queries
     */
    protected function setQueryTimeout(): void
    {
        try {
            // Set MySQL timeout
            \Illuminate\Support\Facades\DB::statement('SET SESSION wait_timeout = 300');
            \Illuminate\Support\Facades\DB::statement('SET SESSION interactive_timeout = 300');
        } catch (\Exception) {
            // Ignore if not MySQL or permission denied
        }
    }

    /**
     * Tối ưu session cho admin
     */
    protected function optimizeSession(Request $request): void
    {
        // Extend session lifetime for admin users
        // Kiểm tra nếu đang truy cập admin panel thì extend session
        if ($request->user() && $this->isFilamentRequest($request)) {
            config(['session.lifetime' => 480]); // 8 hours
        }
    }

    /**
     * Log performance metrics
     */
    protected function logPerformanceMetrics(Request $request, float $executionTime, int $memoryUsed): void
    {
        // Chỉ log nếu request chậm hoặc dùng nhiều memory
        if ($executionTime > 1000 || $memoryUsed > 50 * 1024 * 1024) { // > 1s hoặc > 50MB
            Log::info('Filament Performance Metrics', [
                'url' => $request->fullUrl(),
                'method' => $request->method(),
                'execution_time_ms' => round($executionTime, 2),
                'memory_used_mb' => round($memoryUsed / 1024 / 1024, 2),
                'user_id' => $request->user()?->id,
                'ip' => $request->ip(),
            ]);
        }
    }

    /**
     * Thêm headers tối ưu hóa
     */
    protected function addOptimizationHeaders(Response $response): void
    {
        // Cache headers cho static assets
        if ($this->isStaticAsset($response)) {
            $response->headers->set('Cache-Control', 'public, max-age=31536000'); // 1 year
            $response->headers->set('Expires', gmdate('D, d M Y H:i:s', time() + 31536000) . ' GMT');
        }

        // Compression headers
        if (!$response->headers->has('Content-Encoding')) {
            $response->headers->set('Vary', 'Accept-Encoding');
        }

        // Security headers
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
    }

    /**
     * Kiểm tra xem response có phải static asset không
     */
    protected function isStaticAsset(Response $response): bool
    {
        $contentType = $response->headers->get('Content-Type', '');
        
        return str_contains($contentType, 'css') ||
               str_contains($contentType, 'javascript') ||
               str_contains($contentType, 'image/') ||
               str_contains($contentType, 'font/');
    }

    /**
     * Clear cache có điều kiện
     */
    protected function conditionalCacheClear(Request $request): void
    {
        // Clear cache khi có thao tác CUD
        if (in_array($request->method(), ['POST', 'PUT', 'PATCH', 'DELETE'])) {
            // Clear cache liên quan đến resource được thay đổi
            $path = $request->path();
            
            if (preg_match('/admin\/([^\/]+)/', $path, $matches)) {
                $resource = $matches[1];
                $this->optimizationService->clearCache($resource);
            }
        }
    }

    /**
     * Parse memory limit string to bytes
     */
    protected function parseMemoryLimit(string $limit): int
    {
        $limit = trim($limit);
        $last = strtolower($limit[strlen($limit) - 1]);
        $value = (int) $limit;

        switch ($last) {
            case 'g':
                $value *= 1024;
            case 'm':
                $value *= 1024;
            case 'k':
                $value *= 1024;
        }

        return $value;
    }
}
