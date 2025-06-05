<?php

namespace App\Http\Controllers;

use App\Models\CourseGroup;
use Illuminate\Http\Request;

class CourseGroupController extends Controller
{
    /**
     * Hiển thị danh sách tất cả nhóm khóa học
     */
    public function index()
    {
        $courseGroups = CourseGroup::where('status', 'active')
            ->whereNotNull('group_link')
            ->orderBy('is_featured', 'desc')
            ->orderBy('order', 'asc')
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return view('course-groups.index', compact('courseGroups'));
    }

    /**
     * Hiển thị chi tiết nhóm khóa học
     */
    public function show($slug)
    {
        $courseGroup = CourseGroup::where('slug', $slug)
            ->where('status', 'active')
            ->firstOrFail();

        return view('course-groups.show', compact('courseGroup'));
    }
}
