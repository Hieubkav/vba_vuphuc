<?php

namespace App\Http\Controllers;

use App\Helpers\AvatarHelper;
use App\Models\Testimonial;
use App\Providers\ViewServiceProvider;
use App\Rules\CaptchaRule;
use App\Services\CaptchaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class FeedbackController extends Controller
{
    /**
     * Hiển thị form đóng góp ý kiến
     */
    public function show(): View
    {
        // Tạo CAPTCHA mới cho form
        $captcha = CaptchaService::generate();

        return view('feedback.index', [
            'captcha' => $captcha
        ]);
    }

    /**
     * Xử lý submit form đóng góp ý kiến
     */
    public function store(Request $request): RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'content' => 'required|string|min:10|max:1000',
            'rating' => 'nullable|integer|min:1|max:5',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'captcha' => ['required', new CaptchaRule()],
        ], [
            'name.required' => 'Vui lòng nhập họ và tên',
            'name.max' => 'Họ và tên không được vượt quá 255 ký tự',
            'email.required' => 'Vui lòng nhập email',
            'email.email' => 'Email không đúng định dạng',
            'email.max' => 'Email không được vượt quá 255 ký tự',
            'content.required' => 'Vui lòng nhập nội dung ý kiến',
            'content.min' => 'Nội dung ý kiến phải có ít nhất 10 ký tự',
            'content.max' => 'Nội dung ý kiến không được vượt quá 1000 ký tự',
            'rating.integer' => 'Đánh giá phải là số nguyên',
            'rating.min' => 'Đánh giá tối thiểu là 1 sao',
            'rating.max' => 'Đánh giá tối đa là 5 sao',
            'avatar.image' => 'File avatar phải là hình ảnh',
            'avatar.mimes' => 'Avatar chỉ chấp nhận định dạng: jpeg, png, jpg, webp',
            'avatar.max' => 'Kích thước avatar không được vượt quá 2MB',
            'captcha.required' => 'Vui lòng nhập kết quả xác thực bảo mật',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Vui lòng kiểm tra lại thông tin đã nhập');
        }

        try {
            // Xử lý upload avatar nếu có
            $avatarPath = null;
            if ($request->hasFile('avatar')) {
                $avatarFile = $request->file('avatar');
                $customName = 'feedback-avatar-' . $request->name;

                // Sử dụng ConvertImageToWebp action để tối ưu ảnh
                $avatarPath = \App\Actions\ConvertImageToWebp::run(
                    $avatarFile,
                    'testimonials/avatars',
                    $customName,
                    400,
                    400
                );
            } else {
                // Tạo avatar chữ cái tự động nếu không upload ảnh
                $avatarPath = AvatarHelper::generateAvatarString($request->name);
            }

            // Tạo testimonial mới với trạng thái pending
            Testimonial::create([
                'name' => $request->name,
                'email' => $request->email,
                'content' => $request->content,
                'rating' => $request->rating ?? 5,
                'status' => 'pending', // Trạng thái chưa được duyệt
                'order' => 0,
                'avatar' => $avatarPath,
                'location' => null, // Giữ lại trường location
            ]);

            // Clear cache để cập nhật dữ liệu testimonials trong CMS
            ViewServiceProvider::refreshCache('testimonials');

            return redirect()->route('feedback.success')
                ->with('success', 'Cảm ơn bạn đã gửi ý kiến đóng góp! Chúng tôi sẽ xem xét và phản hồi sớm nhất.');

        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Có lỗi xảy ra khi gửi ý kiến. Vui lòng thử lại sau.');
        }
    }

    /**
     * Hiển thị trang cảm ơn sau khi gửi feedback thành công
     */
    public function success(): View
    {
        return view('feedback.success');
    }
}
