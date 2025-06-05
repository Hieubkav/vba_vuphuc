<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CourseMaterial;
use App\Models\Course;
use Illuminate\Support\Str;

class CourseMaterialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $courses = Course::all();

        if ($courses->isEmpty()) {
            $this->command->warn('Không có khóa học nào. Vui lòng chạy CourseSeeder trước.');
            return;
        }

        $materialTemplates = [
            // Excel VBA Materials
            'excel-vba' => [
                [
                    'title' => 'Giáo trình Excel VBA cơ bản',
                    'description' => 'Tài liệu hướng dẫn Excel VBA từ cơ bản đến nâng cao với các ví dụ thực tế',
                    'file_path' => 'course_materials/excel-vba-basic-guide.pdf',
                    'file_type' => 'pdf',
                    'file_size' => 15728640, // 15MB
                    'access_type' => 'public',
                    'order' => 1
                ],
                [
                    'title' => 'Code mẫu VBA - Bài tập thực hành',
                    'description' => 'Bộ sưu tập code VBA mẫu cho các bài tập thực hành',
                    'file_path' => 'course_materials/excel-vba-code-samples.zip',
                    'file_type' => 'zip',
                    'file_size' => 5242880, // 5MB
                    'access_type' => 'enrolled',
                    'order' => 2
                ],
                [
                    'title' => 'Video hướng dẫn cài đặt VBA',
                    'description' => 'Video hướng dẫn chi tiết cách cài đặt và cấu hình môi trường VBA',
                    'file_path' => 'course_materials/excel-vba-setup-guide.mp4',
                    'file_type' => 'mp4',
                    'file_size' => 104857600, // 100MB
                    'access_type' => 'public',
                    'order' => 3
                ],
                [
                    'title' => 'Cheat Sheet - Các hàm VBA thông dụng',
                    'description' => 'Bảng tóm tắt các hàm VBA thường sử dụng',
                    'file_path' => 'course_materials/excel-vba-cheat-sheet.pdf',
                    'file_type' => 'pdf',
                    'file_size' => 2097152, // 2MB
                    'access_type' => 'public',
                    'order' => 4
                ]
            ],

            // Excel Dashboard Materials
            'excel-dashboard' => [
                [
                    'title' => 'Template Dashboard Excel chuyên nghiệp',
                    'description' => 'Bộ template dashboard Excel có thể tùy chỉnh cho nhiều mục đích khác nhau',
                    'file_path' => 'course_materials/excel-dashboard-templates.xlsx',
                    'file_type' => 'xlsx',
                    'file_size' => 8388608, // 8MB
                    'access_type' => 'enrolled',
                    'order' => 1
                ],
                [
                    'title' => 'Hướng dẫn thiết kế Dashboard',
                    'description' => 'Tài liệu hướng dẫn chi tiết cách thiết kế dashboard hiệu quả',
                    'file_path' => 'course_materials/excel-dashboard-design-guide.pdf',
                    'file_type' => 'pdf',
                    'file_size' => 12582912, // 12MB
                    'access_type' => 'public',
                    'order' => 2
                ],
                [
                    'title' => 'Data mẫu cho thực hành',
                    'description' => 'Bộ dữ liệu mẫu để thực hành tạo dashboard',
                    'file_path' => 'course_materials/excel-dashboard-sample-data.csv',
                    'file_type' => 'csv',
                    'file_size' => 1048576, // 1MB
                    'access_type' => 'public',
                    'order' => 3
                ]
            ],

            // Excel Basic Materials
            'excel-basic' => [
                [
                    'title' => 'Giáo trình Excel cơ bản',
                    'description' => 'Tài liệu học Excel từ con số 0 dành cho người mới bắt đầu',
                    'file_type' => 'pdf',
                    'file_size' => 10485760, // 10MB
                    'access_type' => 'public',
                    'order' => 1
                ],
                [
                    'title' => 'Bài tập thực hành Excel cơ bản',
                    'description' => 'Bộ bài tập thực hành Excel với đáp án chi tiết',
                    'file_type' => 'xlsx',
                    'file_size' => 3145728, // 3MB
                    'access_type' => 'enrolled',
                    'order' => 2
                ],
                [
                    'title' => 'Video giới thiệu giao diện Excel',
                    'description' => 'Video hướng dẫn làm quen với giao diện Excel 2019/365',
                    'file_type' => 'mp4',
                    'file_size' => 52428800, // 50MB
                    'access_type' => 'public',
                    'order' => 3
                ]
            ],

            // Accounting Materials
            'accounting' => [
                [
                    'title' => 'Hệ thống sổ sách kế toán Excel',
                    'description' => 'Template hệ thống sổ sách kế toán hoàn chỉnh trên Excel',
                    'file_type' => 'xlsx',
                    'file_size' => 20971520, // 20MB
                    'access_type' => 'enrolled',
                    'order' => 1
                ],
                [
                    'title' => 'Giáo trình kế toán với Excel',
                    'description' => 'Tài liệu hướng dẫn ứng dụng Excel trong công việc kế toán',
                    'file_type' => 'pdf',
                    'file_size' => 18874368, // 18MB
                    'access_type' => 'public',
                    'order' => 2
                ],
                [
                    'title' => 'Mẫu báo cáo tài chính',
                    'description' => 'Bộ mẫu báo cáo tài chính chuẩn theo quy định',
                    'file_type' => 'xlsx',
                    'file_size' => 6291456, // 6MB
                    'access_type' => 'public',
                    'order' => 3
                ]
            ],

            // Project Management Materials
            'project-management' => [
                [
                    'title' => 'Template quản lý dự án Excel',
                    'description' => 'Bộ template quản lý dự án với Gantt Chart và theo dõi tiến độ',
                    'file_type' => 'xlsx',
                    'file_size' => 15728640, // 15MB
                    'access_type' => 'enrolled',
                    'order' => 1
                ],
                [
                    'title' => 'Hướng dẫn quản lý dự án với Excel',
                    'description' => 'Tài liệu hướng dẫn chi tiết cách quản lý dự án hiệu quả với Excel',
                    'file_type' => 'pdf',
                    'file_size' => 14680064, // 14MB
                    'access_type' => 'public',
                    'order' => 2
                ],
                [
                    'title' => 'Case study - Dự án thực tế',
                    'description' => 'Nghiên cứu tình huống dự án thực tế và cách giải quyết',
                    'file_type' => 'pdf',
                    'file_size' => 8388608, // 8MB
                    'access_type' => 'public',
                    'order' => 3
                ]
            ]
        ];

        foreach ($courses as $course) {
            $materials = $this->getMaterialsForCourse($course, $materialTemplates);

            foreach ($materials as $materialData) {
                // Add file_path and file_name if not exists
                if (!isset($materialData['file_path'])) {
                    $materialData['file_path'] = $this->generateFilePath($materialData['title'], $materialData['file_type']);
                }
                if (!isset($materialData['file_name'])) {
                    $materialData['file_name'] = $this->generateFileName($materialData['title'], $materialData['file_type']);
                }

                CourseMaterial::create(array_merge($materialData, [
                    'course_id' => $course->id,
                    'is_preview' => false, // Mặc định false vì chúng ta không sử dụng nữa
                    'access_type' => $materialData['access_type'] ?? 'public', // Đảm bảo có access_type
                    'material_type' => 'document' // Mặc định là document
                ]));
            }
        }

        $this->command->info('Đã tạo thành công tài liệu cho tất cả khóa học!');
    }

    /**
     * Lấy materials phù hợp cho từng khóa học
     */
    private function getMaterialsForCourse($course, $materialTemplates)
    {
        // Map course slug to material type
        $courseSlugToMaterialType = [
            'excel-vba-tu-co-ban-den-nang-cao' => 'excel-vba',
            'excel-dashboard-bao-cao-chuyen-nghiep' => 'excel-dashboard',
            'excel-co-ban-cho-nguoi-moi-bat-dau' => 'excel-basic',
            'ke-toan-doanh-nghiep-voi-excel' => 'accounting',
            'quan-ly-du-an-voi-excel' => 'project-management'
        ];

        $materialType = $courseSlugToMaterialType[$course->slug] ?? 'excel-basic';

        return $materialTemplates[$materialType] ?? $materialTemplates['excel-basic'];
    }

    /**
     * Generate file path from title and file type
     */
    private function generateFilePath($title, $fileType)
    {
        $slug = Str::slug($title);
        return "course_materials/{$slug}.{$fileType}";
    }

    /**
     * Generate file name from title and file type
     */
    private function generateFileName($title, $fileType)
    {
        $slug = Str::slug($title);
        return "{$slug}.{$fileType}";
    }
}
