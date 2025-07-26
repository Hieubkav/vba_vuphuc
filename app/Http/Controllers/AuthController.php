<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Rules\CaptchaRule;
use App\Services\CaptchaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class AuthController extends Controller
{
    /**
     * Hiển thị form đăng nhập
     */
    public function showLoginForm(Request $request): View
    {
        // Lưu URL redirect nếu có
        if ($request->has('redirect')) {
            session(['url.intended' => $request->get('redirect')]);
        }

        return view('auth.login');
    }

    /**
     * Xử lý đăng nhập
     */
    public function login(Request $request): RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ], [
            'email.required' => 'Vui lòng nhập email',
            'email.email' => 'Email không đúng định dạng',
            'password.required' => 'Vui lòng nhập mật khẩu',
            'password.min' => 'Mật khẩu phải có ít nhất 6 ký tự',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput($request->only('email'))
                ->with('error', 'Vui lòng kiểm tra lại thông tin đăng nhập');
        }

        $student = Student::where('email', $request->email)->first();

        if (!$student || !Hash::check($request->password, $student->password)) {
            return back()
                ->withInput($request->only('email'))
                ->with('error', 'Email hoặc mật khẩu không chính xác');
        }

        if ($student->status !== 'active') {
            return back()
                ->withInput($request->only('email'))
                ->with('error', 'Tài khoản của bạn đã bị khóa. Vui lòng liên hệ admin');
        }

        // Đăng nhập thành công
        Auth::guard('student')->login($student, $request->filled('remember'));

        // Redirect về trang trước đó hoặc trang chủ nếu không có
        $intendedUrl = session()->pull('url.intended', route('storeFront'));

        return redirect($intendedUrl)
            ->with('success', 'Đăng nhập thành công! Chào mừng ' . $student->name);
    }

    /**
     * Hiển thị form đăng ký
     */
    public function showRegisterForm(Request $request): View
    {
        // Lưu URL redirect nếu có
        if ($request->has('redirect')) {
            session(['url.intended' => $request->get('redirect')]);
        }

        // Tạo CAPTCHA mới cho form đăng ký
        $captcha = CaptchaService::generate();

        return view('auth.register', [
            'captcha' => $captcha
        ]);
    }

    /**
     * Xử lý đăng ký
     */
    public function register(Request $request): RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:students,email',
            'password' => 'required|string|min:6|confirmed',
            'phone' => 'nullable|string|max:20',
            'birth_date' => 'nullable|date|before:' . now()->subYears(16)->format('Y-m-d'),
            'gender' => 'nullable|in:male,female,other',
            'address' => 'nullable|string|max:500',
            'occupation' => 'nullable|string|max:255',
            'education_level' => 'nullable|in:high_school,college,university,master,phd,other',
            'learning_goals' => 'nullable|string|max:1000',
            'interests' => 'nullable|array',
            'captcha' => ['required', new CaptchaRule()],
        ], [
            'name.required' => 'Vui lòng nhập họ và tên',
            'email.required' => 'Vui lòng nhập email',
            'email.email' => 'Email không đúng định dạng',
            'email.unique' => 'Email này đã được đăng ký',
            'password.required' => 'Vui lòng nhập mật khẩu',
            'password.min' => 'Mật khẩu phải có ít nhất 6 ký tự',
            'password.confirmed' => 'Xác nhận mật khẩu không khớp',
            'birth_date.before' => 'Bạn phải từ 16 tuổi trở lên',
            'captcha.required' => 'Vui lòng nhập kết quả xác thực bảo mật',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Vui lòng kiểm tra lại thông tin đăng ký');
        }

        $data = $validator->validated();
        $data['password'] = Hash::make($data['password']);
        $data['status'] = 'active';

        $student = Student::create($data);

        // Tự động đăng nhập sau khi đăng ký
        Auth::guard('student')->login($student);

        // Redirect về trang trước đó hoặc trang chủ nếu không có
        $intendedUrl = session()->pull('url.intended', route('storeFront'));

        return redirect($intendedUrl)
            ->with('success', 'Đăng ký thành công! Chào mừng bạn đến với VBA Vũ Phúc');
    }

    /**
     * Đăng xuất
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::guard('student')->logout();

        // Lấy URL từ referer hoặc về trang chủ
        $redirectUrl = $request->header('referer', route('storeFront'));

        return redirect($redirectUrl)
            ->with('success', 'Đăng xuất thành công!');
    }
}
