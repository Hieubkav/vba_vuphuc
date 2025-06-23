<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;

class HandleFileUploadErrors
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            // Kiểm tra và tạo thư mục livewire-tmp nếu chưa tồn tại
            $this->ensureLivewireTmpDirectory();

            return $next($request);
        } catch (\Exception $e) {
            // Log lỗi file upload
            if (str_contains($e->getMessage(), 'file_size') || str_contains($e->getMessage(), 'livewire-tmp')) {
                Log::error('File upload error: ' . $e->getMessage(), [
                    'url' => $request->url(),
                    'method' => $request->method(),
                    'user_agent' => $request->userAgent(),
                    'ip' => $request->ip(),
                ]);

                // Trả về response lỗi thân thiện
                if ($request->expectsJson()) {
                    return response()->json([
                        'error' => 'Có lỗi xảy ra khi tải file lên. Vui lòng thử lại.',
                        'message' => 'File upload failed'
                    ], 500);
                }

                return redirect()->back()->with('error', 'Có lỗi xảy ra khi tải file lên. Vui lòng thử lại.');
            }

            throw $e;
        }
    }

    /**
     * Đảm bảo thư mục livewire-tmp tồn tại và có quyền ghi
     */
    private function ensureLivewireTmpDirectory(): void
    {
        $tmpPath = storage_path('app/livewire-tmp');

        if (!is_dir($tmpPath)) {
            mkdir($tmpPath, 0755, true);
            Log::info('Created livewire-tmp directory: ' . $tmpPath);
        }

        if (!is_writable($tmpPath)) {
            chmod($tmpPath, 0755);
            Log::info('Fixed permissions for livewire-tmp directory: ' . $tmpPath);
        }
    }
}
