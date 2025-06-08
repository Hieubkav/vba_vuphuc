<?php

namespace App\Http\Controllers;

use App\Actions\ConvertImageToFavicon;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class FaviconController extends Controller
{
    /**
     * Hiển thị form upload favicon
     */
    public function index()
    {
        return view('admin.favicon-upload');
    }

    /**
     * Xử lý upload và convert favicon
     */
    public function upload(Request $request): JsonResponse
    {
        try {
            // Validation với custom error handling
            $validator = Validator::make($request->all(), [
                'favicon' => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048'
            ], [
                'favicon.required' => 'Vui lòng chọn file ảnh.',
                'favicon.image' => 'File phải là ảnh.',
                'favicon.mimes' => 'Chỉ hỗ trợ file JPG, PNG, GIF, SVG, WebP.',
                'favicon.max' => 'File không được vượt quá 2MB.'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first()
                ], 422);
            }

            $file = $request->file('favicon');

            // Gọi action để convert
            $success = ConvertImageToFavicon::run($file);

            if ($success) {
                return response()->json([
                    'success' => true,
                    'message' => 'Favicon đã được cập nhật thành công!'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Có lỗi xảy ra khi convert favicon.'
                ], 500);
            }

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->validator->errors()->first()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Favicon upload error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }
}
