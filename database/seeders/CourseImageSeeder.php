<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CourseImage;
use App\Models\Course;

class CourseImageSeeder extends Seeder
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

        // Template ảnh cho các loại khóa học
        $imageTemplates = [
            'excel-vba' => [
                [
                    'image_link' => 'courses/excel-vba-main.jpg',
                    'alt_text' => 'Khóa học Excel VBA từ cơ bản đến nâng cao',
                    'is_main' => true,
                    'order' => 1
                ],
                [
                    'image_link' => 'courses/excel-vba-code.jpg',
                    'alt_text' => 'Giao diện lập trình VBA trong Excel',
                    'is_main' => false,
                    'order' => 2
                ],
                [
                    'image_link' => 'courses/excel-vba-userform.jpg',
                    'alt_text' => 'Thiết kế UserForm trong VBA',
                    'is_main' => false,
                    'order' => 3
                ]
            ],
            'excel-dashboard' => [
                [
                    'image_link' => 'courses/excel-dashboard-main.jpg',
                    'alt_text' => 'Dashboard Excel chuyên nghiệp',
                    'is_main' => true,
                    'order' => 1
                ],
                [
                    'image_link' => 'courses/excel-dashboard-charts.jpg',
                    'alt_text' => 'Biểu đồ tương tác trong Excel Dashboard',
                    'is_main' => false,
                    'order' => 2
                ]
            ],
            'excel-basic' => [
                [
                    'image_link' => 'courses/excel-basic-main.jpg',
                    'alt_text' => 'Học Excel cơ bản cho người mới bắt đầu',
                    'is_main' => true,
                    'order' => 1
                ],
                [
                    'image_link' => 'courses/excel-basic-interface.jpg',
                    'alt_text' => 'Giao diện Excel cơ bản',
                    'is_main' => false,
                    'order' => 2
                ]
            ],
            'accounting' => [
                [
                    'image_link' => 'courses/accounting-main.jpg',
                    'alt_text' => 'Kế toán doanh nghiệp với Excel',
                    'is_main' => true,
                    'order' => 1
                ],
                [
                    'image_link' => 'courses/accounting-reports.jpg',
                    'alt_text' => 'Báo cáo tài chính Excel',
                    'is_main' => false,
                    'order' => 2
                ]
            ],
            'project-management' => [
                [
                    'image_link' => 'courses/project-management-main.jpg',
                    'alt_text' => 'Quản lý dự án với Excel',
                    'is_main' => true,
                    'order' => 1
                ],
                [
                    'image_link' => 'courses/project-gantt-chart.jpg',
                    'alt_text' => 'Gantt Chart trong Excel',
                    'is_main' => false,
                    'order' => 2
                ]
            ]
        ];

        foreach ($courses as $course) {
            $images = $this->getImagesForCourse($course, $imageTemplates);

            foreach ($images as $imageData) {
                CourseImage::create(array_merge($imageData, [
                    'course_id' => $course->id,
                    'status' => 'active'
                ]));
            }
        }

        $this->command->info('Đã tạo thành công hình ảnh cho tất cả khóa học!');
    }

    /**
     * Lấy images phù hợp cho từng khóa học
     */
    private function getImagesForCourse($course, $imageTemplates)
    {
        // Map course slug to image type
        $courseSlugToImageType = [
            'excel-vba-tu-co-ban-den-nang-cao' => 'excel-vba',
            'excel-dashboard-bao-cao-chuyen-nghiep' => 'excel-dashboard',
            'excel-co-ban-cho-nguoi-moi-bat-dau' => 'excel-basic',
            'ke-toan-doanh-nghiep-voi-excel' => 'accounting',
            'quan-ly-du-an-voi-excel' => 'project-management'
        ];

        $imageType = $courseSlugToImageType[$course->slug] ?? 'excel-basic';

        return $imageTemplates[$imageType] ?? $imageTemplates['excel-basic'];
    }
}
