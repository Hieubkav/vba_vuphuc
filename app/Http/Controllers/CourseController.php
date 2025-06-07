<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\CatPost;
use App\Models\CatCourse;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;

class CourseController extends Controller
{
    /**
     * Hiển thị danh sách tất cả khóa học với filter
     */
    public function index(Request $request): View
    {
        $query = Course::with(['courseCategory', 'instructor', 'images' => function($q) {
            $q->where('status', 'active')->orderBy('is_main', 'desc')->orderBy('order');
        }])
        ->where('status', 'active');

        // Filter theo category - Sử dụng courseCategory (CatCourse)
        if ($request->filled('category')) {
            $query->whereHas('courseCategory', function($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        // Filter theo level
        if ($request->filled('level')) {
            $query->where('level', $request->level);
        }

        // Filter theo price range
        if ($request->filled('price_min')) {
            $query->where('price', '>=', $request->price_min);
        }
        if ($request->filled('price_max')) {
            $query->where('price', '<=', $request->price_max);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhereHas('instructor', function($subQ) use ($search) {
                      $subQ->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Sort
        $sort = $request->get('sort', 'order');
        switch ($sort) {
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'newest':
                $query->orderBy('created_at', 'desc');
                break;
            case 'popular':
                $query->withCount('students')->orderBy('students_count', 'desc');
                break;
            default:
                $query->orderBy('is_featured', 'desc')->orderBy('order', 'asc');
        }

        $courses = $query->paginate(12)->withQueryString();

        // Get categories for filter - Sử dụng CatCourse thay vì CatPost
        $categories = Cache::remember('course_categories', 3600, function() {
            return \App\Models\CatCourse::where('status', 'active')
                ->whereHas('courses', function($q) {
                    $q->where('status', 'active');
                })
                ->withCount(['courses' => function($q) {
                    $q->where('status', 'active');
                }])
                ->orderBy('order')
                ->get();
        });

        // Get filter stats
        $stats = [
            'total' => Course::where('status', 'active')->count(),
            'levels' => Course::where('status', 'active')
                ->select('level', DB::raw('count(*) as count'))
                ->groupBy('level')
                ->pluck('count', 'level'),
            'price_range' => [
                'min' => Course::where('status', 'active')->min('price'),
                'max' => Course::where('status', 'active')->max('price'),
            ]
        ];

        return view('courses.index', compact('courses', 'categories', 'stats'));
    }

    /**
     * Hiển thị chi tiết khóa học
     */
    public function show(string $slug): View
    {
        $course = Course::with([
            'courseCategory',
            'instructor',
            'courseGroup', // Thêm quan hệ courseGroup để lấy group_link
            'images' => function($q) {
                $q->where('status', 'active')->orderBy('is_main', 'desc')->orderBy('order');
            },
            'materials' => function($q) {
                $q->orderBy('order');
            },
            'students' => function($q) {
                $q->wherePivot('status', '!=', 'dropped');
            }
        ])
        ->where('slug', $slug)
        ->where('status', 'active')
        ->firstOrFail();

        // Get related courses - chỉ lấy khóa học cùng danh mục khóa học (cat_course_id) - giảm từ 4 xuống 3
        $relatedCourses = Course::with(['courseCategory', 'instructor', 'images' => function($q) {
            $q->where('status', 'active')->orderBy('is_main', 'desc')->take(1);
        }])
        ->where('status', 'active')
        ->where('id', '!=', $course->id)
        ->where('cat_course_id', $course->cat_course_id)
        ->whereNotNull('cat_course_id')
        ->orderBy('is_featured', 'desc')
        ->orderBy('order')
        ->take(3)
        ->get();

        // Get open materials (tài liệu mở - ai cũng xem và tải được)
        $openMaterials = $course->materials()
            ->where('access_type', 'public')
            ->orderBy('order')
            ->get();

        // Get enrolled materials (tài liệu dành cho học viên - hiển thị với icon khóa nếu chưa đăng nhập)
        $enrolledMaterials = $course->materials()
            ->where('access_type', 'enrolled')
            ->orderBy('order')
            ->get();

        // Kiểm tra user hiện tại có đăng ký khóa học không
        $user = auth()->user();
        $isEnrolled = false;
        if ($user) {
            $isEnrolled = $course->students()->where('student_id', $user->id)->exists();
        }

        return view('courses.show', compact(
            'course',
            'relatedCourses',
            'openMaterials',
            'enrolledMaterials',
            'isEnrolled'
        ));
    }

    /**
     * Hiển thị khóa học theo danh mục
     */
    public function category(string $slug): View
    {
        $category = CatCourse::where('slug', $slug)
            ->where('status', 'active')
            ->firstOrFail();

        $courses = Course::with(['courseCategory', 'images' => function($q) {
            $q->where('status', 'active')->orderBy('is_main', 'desc')->orderBy('order');
        }])
        ->where('cat_course_id', $category->id)
        ->where('status', 'active')
        ->orderBy('is_featured', 'desc')
        ->orderBy('order')
        ->paginate(12);

        return view('courses.category', compact('category', 'courses'));
    }

    /**
     * Hiển thị khóa học theo danh mục khóa học (CatCourse)
     */
    public function catCategory(string $slug): View
    {
        $category = CatCourse::where('slug', $slug)
            ->where('status', 'active')
            ->firstOrFail();

        $courses = Course::with(['courseCategory', 'images' => function($q) {
            $q->where('status', 'active')->orderBy('is_main', 'desc')->orderBy('order');
        }])
        ->where('cat_course_id', $category->id)
        ->where('status', 'active')
        ->orderBy('is_featured', 'desc')
        ->orderBy('order')
        ->paginate(12);

        return view('courses.cat-category', compact('category', 'courses'));
    }

    /**
     * API endpoint cho search suggestions
     */
    public function searchSuggestions(Request $request): JsonResponse
    {
        $query = $request->get('q', '');

        if (strlen($query) < 1) {
            return response()->json([]);
        }

        $courses = Course::where('status', 'active')
            ->where(function($q) use ($query) {
                $q->where('title', 'like', "%{$query}%")
                  ->orWhereHas('instructor', function($q) use ($query) {
                      $q->where('name', 'like', "%{$query}%");
                  });
            })
            ->with(['courseCategory', 'instructor'])
            ->select(['id', 'title', 'slug', 'thumbnail', 'price', 'cat_course_id', 'instructor_id'])
            ->orderBy('is_featured', 'desc')
            ->take(8)
            ->get();

        return response()->json($courses->map(function($course) {
            return [
                'id' => $course->id,
                'title' => $course->title,
                'slug' => $course->slug,
                'url' => route('courses.show', $course->slug),
                'thumbnail' => $course->thumbnail ? asset('storage/' . $course->thumbnail) : null,
                'price' => $course->formatted_price,
                'instructor' => $course->instructor?->name,
                'category' => $course->category?->name,
            ];
        }));
    }
}
