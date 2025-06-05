<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;

class StudentController extends Controller
{
    /**
     * Hiển thị form đăng ký học viên
     */
    public function register(): View
    {
        return view('students.register');
    }

    /**
     * Xử lý đăng ký học viên mới
     */
    public function store(Request $request): RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:students,email',
            'phone' => 'nullable|string|max:20',
            'birth_date' => 'nullable|date|before:' . now()->subYears(16)->format('Y-m-d'),
            'gender' => 'nullable|in:male,female,other',
            'address' => 'nullable|string|max:500',
            'occupation' => 'nullable|string|max:255',
            'education_level' => 'nullable|in:high_school,college,university,master,phd,other',
            'learning_goals' => 'nullable|string|max:1000',
            'interests' => 'nullable|array',
            'avatar' => 'nullable|image|max:2048',
        ], [
            'name.required' => 'Vui lòng nhập họ và tên',
            'email.required' => 'Vui lòng nhập email',
            'email.email' => 'Email không đúng định dạng',
            'email.unique' => 'Email này đã được đăng ký',
            'birth_date.before' => 'Bạn phải từ 16 tuổi trở lên',
            'avatar.image' => 'File phải là hình ảnh',
            'avatar.max' => 'Kích thước ảnh không được vượt quá 2MB',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Vui lòng kiểm tra lại thông tin đăng ký');
        }

        $data = $validator->validated();

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            $data['avatar'] = $request->file('avatar')->store('students/avatars', 'public');
        }

        // Create student
        $student = Student::create($data);

        return redirect()
            ->route('students.success')
            ->with('success', 'Đăng ký thành công! Chúng tôi sẽ liên hệ với bạn sớm nhất.');
    }

    /**
     * Hiển thị trang đăng ký thành công
     */
    public function success(): View
    {
        return view('students.success');
    }

    /**
     * Đăng ký khóa học cho học viên
     */
    public function enrollCourse(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'course_id' => 'required|exists:courses,id',
            'student_name' => 'required|string|max:255',
            'student_email' => 'required|email',
            'student_phone' => 'required|string|max:20',
            'message' => 'nullable|string|max:500',
        ], [
            'course_id.required' => 'Vui lòng chọn khóa học',
            'course_id.exists' => 'Khóa học không tồn tại',
            'student_name.required' => 'Vui lòng nhập họ và tên',
            'student_email.required' => 'Vui lòng nhập email',
            'student_email.email' => 'Email không đúng định dạng',
            'student_phone.required' => 'Vui lòng nhập số điện thoại',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Vui lòng kiểm tra lại thông tin',
                'errors' => $validator->errors()
            ], 422);
        }

        $course = Course::findOrFail($request->course_id);

        // Check if course is full
        if ($course->is_full) {
            return response()->json([
                'success' => false,
                'message' => 'Khóa học đã đầy, vui lòng chọn khóa học khác'
            ], 400);
        }

        // Find or create student
        $student = Student::firstOrCreate(
            ['email' => $request->student_email],
            [
                'name' => $request->student_name,
                'phone' => $request->student_phone,
                'status' => 'active'
            ]
        );

        // Check if already enrolled
        if ($student->isEnrolledIn($course->id)) {
            return response()->json([
                'success' => false,
                'message' => 'Bạn đã đăng ký khóa học này rồi'
            ], 400);
        }

        // Enroll student
        $student->courses()->attach($course->id, [
            'enrolled_at' => now(),
            'status' => 'enrolled',
            'notes' => $request->message,
        ]);

        // TODO: Send notification email

        return response()->json([
            'success' => true,
            'message' => 'Đăng ký khóa học thành công! Chúng tôi sẽ liên hệ với bạn sớm nhất.'
        ]);
    }

    /**
     * Hiển thị thông tin học viên (cần authentication)
     */
    public function profile(Request $request): View
    {
        // TODO: Implement authentication for students
        $student = Student::where('email', $request->email)->firstOrFail();

        $enrolledCourses = $student->courses()
            ->with(['category', 'images' => function($q) {
                $q->where('status', 'active')->orderBy('is_main', 'desc')->take(1);
            }])
            ->wherePivot('status', '!=', 'dropped')
            ->orderBy('course_student.enrolled_at', 'desc')
            ->get();

        return view('students.profile', compact('student', 'enrolledCourses'));
    }
}
