<?php

namespace App\Http\Controllers;

use App\Models\Student;
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
    public function showLoginForm(): View
    {
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

        return redirect()->intended(route('storeFront'))
            ->with('success', 'Đăng nhập thành công! Chào mừng ' . $student->name);
    }

    /**
     * Hiển thị form đăng ký
     */
    public function showRegisterForm(): View
    {
        return view('auth.register');
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
        ], [
            'name.required' => 'Vui lòng nhập họ và tên',
            'email.required' => 'Vui lòng nhập email',
            'email.email' => 'Email không đúng định dạng',
            'email.unique' => 'Email này đã được đăng ký',
            'password.required' => 'Vui lòng nhập mật khẩu',
            'password.min' => 'Mật khẩu phải có ít nhất 6 ký tự',
            'password.confirmed' => 'Xác nhận mật khẩu không khớp',
            'birth_date.before' => 'Bạn phải từ 16 tuổi trở lên',
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

        return redirect()->route('storeFront')
            ->with('success', 'Đăng ký thành công! Chào mừng bạn đến với VBA Vũ Phúc');
    }

    /**
     * Đăng xuất
     */
    public function logout(): RedirectResponse
    {
        Auth::guard('student')->logout();
        
        return redirect()->route('storeFront')
            ->with('success', 'Đăng xuất thành công!');
    }
}
