<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\Log;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            // Log file upload errors với thông tin chi tiết
            if (str_contains($e->getMessage(), 'file_size') ||
                str_contains($e->getMessage(), 'livewire-tmp') ||
                str_contains($e->getMessage(), 'Unable to retrieve')) {

                Log::error('File Upload Error', [
                    'message' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'trace' => $e->getTraceAsString(),
                    'url' => request()->url() ?? 'N/A',
                    'user_agent' => request()->userAgent() ?? 'N/A',
                ]);
            }
        });
    }

    /**
     * Render an exception into an HTTP response.
     */
    public function render($request, Throwable $e)
    {
        // Xử lý lỗi file upload
        if (str_contains($e->getMessage(), 'file_size') ||
            str_contains($e->getMessage(), 'livewire-tmp') ||
            str_contains($e->getMessage(), 'Unable to retrieve')) {

            if ($request->expectsJson()) {
                return response()->json([
                    'error' => 'Có lỗi xảy ra khi tải file lên. Vui lòng thử lại.',
                    'message' => 'File upload failed'
                ], 500);
            }

            return redirect()->back()
                ->with('error', 'Có lỗi xảy ra khi tải file lên. Vui lòng kiểm tra file và thử lại.')
                ->withInput();
        }

        return parent::render($request, $e);
    }
}
