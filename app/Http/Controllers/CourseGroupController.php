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
            ->orderBy('order', 'asc')
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return view('course-groups.index', compact('courseGroups'));
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
