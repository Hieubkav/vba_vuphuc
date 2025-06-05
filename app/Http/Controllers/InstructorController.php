<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\Instructor;
use App\Models\Course;

class InstructorController extends Controller
{
    /**
     * Hiển thị chi tiết giảng viên
     */
    public function show(string $slug): View
    {
        $instructor = Instructor::where('slug', $slug)
            ->where('status', 'active')
            ->firstOrFail();

        // Lấy các khóa học của giảng viên
        $courses = Course::with(['courseCategory', 'images' => function($q) {
            $q->where('status', 'active')->orderBy('is_main', 'desc')->take(1);
        }])
        ->where('instructor_id', $instructor->id)
        ->where('status', 'active')
        ->orderBy('is_featured', 'desc')
        ->orderBy('order')
        ->paginate(12);

        return view('instructors.show', compact('instructor', 'courses'));
    }
}
