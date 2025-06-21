<?php

namespace App\Http\Controllers;

use App\Models\CourseGroup;

class CourseGroupController extends Controller
{
    /**
     * Hiển thị danh sách tất cả nhóm khóa học với filter
     */
    public function index()
    {
        return view('course-groups.index');
    }

    /**
     * Hiển thị chi tiết nhóm khóa học (không cần thiết vì chỉ redirect đến link nhóm)
     */
    public function show($id)
    {
        $courseGroup = CourseGroup::where('id', $id)
            ->where('status', 'active')
            ->firstOrFail();

        // Redirect trực tiếp đến link nhóm
        if ($courseGroup->group_link) {
            return redirect()->away($courseGroup->group_link);
        }

        abort(404);
    }
}
