<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Course;
use App\Models\CatCourse;
use App\Models\Instructor;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('📚 Tạo dữ liệu khóa học...');

        // Lấy các danh mục khóa học đã tạo
        $categories = CatCourse::pluck('id', 'slug')->toArray();
        $instructors = Instructor::pluck('id', 'slug')->toArray();

        // Dữ liệu khóa học làm bánh mẫu
        $courses = [
            // Khóa học bánh cơ bản
            [
                'title' => 'Làm bánh cơ bản từ A đến Z',
                'description' => '<p>Khóa học làm bánh cơ bản toàn diện dành cho người mới bắt đầu, từ những kỹ thuật nền tảng đến hoàn thiện sản phẩm.</p>
                    <h3>Nội dung khóa học:</h3>
                    <ul>
                        <li>Giới thiệu về nguyên liệu và dụng cụ làm bánh</li>
                        <li>Kỹ thuật trộn bột và nướng bánh cơ bản</li>
                        <li>Làm bánh bông lan, bánh quy, bánh mì</li>
                        <li>Kem tươi và các loại kem cơ bản</li>
                        <li>Trang trí bánh đơn giản và đẹp mắt</li>
                        <li>Bảo quản và đóng gói sản phẩm</li>
                    </ul>',
                'slug' => 'lam-banh-co-ban-tu-a-den-z',
                'thumbnail' => 'courses/lam-banh-co-ban.webp',
                'price' => 1800000,
                'compare_price' => 2500000,
                'duration_hours' => 32,
                'level' => 'beginner',
                'status' => 'active',
                'is_featured' => true,
                'order' => 1,
                'max_students' => 20,
                'start_date' => now()->addDays(10),
                'end_date' => now()->addDays(50),
                'requirements' => json_encode([
                    'Không cần kinh nghiệm trước đó',
                    'Đam mê làm bánh và học hỏi',
                    'Chuẩn bị dụng cụ cơ bản theo hướng dẫn'
                ]),
                'what_you_learn' => json_encode([
                    'Nắm vững các kỹ thuật làm bánh cơ bản',
                    'Làm được các loại bánh phổ biến',
                    'Trang trí bánh đơn giản nhưng đẹp mắt',
                    'Hiểu về nguyên liệu và cách bảo quản',
                    'Tự tin bắt đầu kinh doanh bánh tại nhà'
                ]),
                'gg_form' => 'https://forms.gle/lam-banh-co-ban',
                'group_link' => 'https://zalo.me/g/lam-banh-co-ban',
                'show_form_link' => true,
                'show_group_link' => true,
                'show_instructor' => true,
                'show_price' => true,
                'cat_course_id' => $categories['banh-co-ban'] ?? null,
                'instructor_id' => $instructors['vu-phuc'] ?? null,
                'seo_title' => 'Khóa học làm bánh cơ bản từ A đến Z - VBA Vũ Phúc',
                'seo_description' => 'Học làm bánh từ cơ bản với chuyên gia. Phù hợp người mới bắt đầu. Đăng ký ngay để nhận ưu đãi!'
            ],
            [
                'title' => 'Bánh Pháp cổ điển và hiện đại',
                'description' => '<p>Khóa học chuyên sâu về bánh Pháp từ những công thức cổ điển đến các biến tấu hiện đại, phù hợp cho người có kinh nghiệm cơ bản.</p>
                    <h3>Bạn sẽ học được:</h3>
                    <ul>
                        <li>Các loại bánh Pháp cổ điển: Croissant, Pain au chocolat</li>
                        <li>Kỹ thuật làm bột lá và tạo lớp</li>
                        <li>Macaron và các loại bánh ngọt tinh tế</li>
                        <li>Tart và Éclair chuyên nghiệp</li>
                        <li>Biến tấu hiện đại của bánh Pháp truyền thống</li>
                    </ul>',
                'slug' => 'banh-phap-co-dien-va-hien-dai',
                'thumbnail' => 'courses/banh-phap-co-dien.webp',
                'price' => 2800000,
                'compare_price' => 3500000,
                'duration_hours' => 40,
                'level' => 'intermediate',
                'status' => 'active',
                'is_featured' => true,
                'order' => 2,
                'max_students' => 15,
                'requirements' => json_encode([
                    'Có kinh nghiệm làm bánh cơ bản',
                    'Hiểu về kỹ thuật trộn bột và nướng',
                    'Kiên nhẫn và tỉ mỉ trong thực hiện'
                ]),
                'what_you_learn' => json_encode([
                    'Làm thành thạo các loại bánh Pháp cổ điển',
                    'Nắm vững kỹ thuật làm bột lá chuyên nghiệp',
                    'Trang trí bánh theo phong cách Pháp tinh tế',
                    'Hiểu về văn hóa và lịch sử bánh Pháp'
                ]),
                'gg_form' => 'https://forms.gle/banh-phap-co-dien',
                'group_link' => 'https://zalo.me/g/banh-phap-group',
                'show_form_link' => true,
                'show_group_link' => true,
                'show_instructor' => true,
                'show_price' => true,
                'cat_course_id' => $categories['banh-nang-cao'] ?? null,
                'instructor_id' => $instructors['minh-anh'] ?? null,
            ],
            // Thêm các khóa học khác
            [
                'title' => 'Bánh mì Việt Nam truyền thống',
                'description' => '<p>Khóa học chuyên sâu về bánh mì Việt Nam, từ bánh mì que truyền thống đến các loại bánh mì hiện đại.</p>',
                'slug' => 'banh-mi-viet-nam-truyen-thong',
                'thumbnail' => 'courses/banh-mi-viet-nam.webp',
                'price' => 1500000,
                'compare_price' => 2000000,
                'duration_hours' => 24,
                'level' => 'beginner',
                'status' => 'active',
                'is_featured' => false,
                'order' => 3,
                'max_students' => 25,
                'requirements' => json_encode(['Đam mê bánh mì và văn hóa Việt']),
                'what_you_learn' => json_encode(['Làm bánh mì Việt Nam chính gốc']),
                'gg_form' => 'https://forms.gle/banh-mi-viet-nam',
                'group_link' => 'https://zalo.me/g/banh-mi-group',
                'show_form_link' => true,
                'show_group_link' => true,
                'show_instructor' => true,
                'show_price' => true,
                'cat_course_id' => $categories['banh-co-ban'] ?? null,
                'instructor_id' => $instructors['duc-minh'] ?? null,
            ],
            [
                'title' => 'Trang trí bánh sinh nhật chuyên nghiệp',
                'description' => '<p>Khóa học trang trí bánh sinh nhật từ cơ bản đến nâng cao, tạo ra những chiếc bánh đẹp mắt và ấn tượng.</p>',
                'slug' => 'trang-tri-banh-sinh-nhat-chuyen-nghiep',
                'thumbnail' => 'courses/trang-tri-banh-sinh-nhat.webp',
                'price' => 2200000,
                'compare_price' => 3000000,
                'duration_hours' => 36,
                'level' => 'intermediate',
                'status' => 'active',
                'is_featured' => true,
                'order' => 4,
                'max_students' => 18,
                'requirements' => json_encode(['Có kiến thức làm bánh cơ bản']),
                'what_you_learn' => json_encode(['Trang trí bánh sinh nhật chuyên nghiệp và đẹp mắt']),
                'gg_form' => 'https://forms.gle/trang-tri-banh-sinh-nhat',
                'group_link' => 'https://zalo.me/g/trang-tri-banh-group',
                'show_form_link' => true,
                'show_group_link' => true,
                'show_instructor' => true,
                'show_price' => true,
                'cat_course_id' => $categories['banh-nang-cao'] ?? null,
                'instructor_id' => $instructors['minh-anh'] ?? null,
            ],
            [
                'title' => 'Bánh ngọt Âu cao cấp',
                'description' => '<p>Khóa học chuyên sâu về bánh ngọt Âu cao cấp, từ Opera cake đến Mille-feuille.</p>',
                'slug' => 'banh-ngot-au-cao-cap',
                'thumbnail' => 'courses/banh-ngot-au-cao-cap.webp',
                'price' => 3200000,
                'compare_price' => 4200000,
                'duration_hours' => 48,
                'level' => 'advanced',
                'status' => 'active',
                'is_featured' => true,
                'order' => 5,
                'max_students' => 12,
                'requirements' => json_encode(['Thành thạo làm bánh nâng cao', 'Có kinh nghiệm với bánh Pháp']),
                'what_you_learn' => json_encode(['Làm bánh ngọt Âu cao cấp chuyên nghiệp', 'Kỹ thuật trang trí tinh tế']),
                'gg_form' => 'https://forms.gle/banh-ngot-au-cao-cap',
                'group_link' => 'https://zalo.me/g/banh-ngot-au-group',
                'show_form_link' => true,
                'show_group_link' => true,
                'show_instructor' => true,
                'show_price' => true,
                'cat_course_id' => $categories['banh-nang-cao'] ?? null,
                'instructor_id' => $instructors['vu-phuc'] ?? null,
            ],
            [
                'title' => 'Workshop làm bánh Macaron',
                'description' => '<p>Workshop thực hành chuyên sâu về kỹ thuật làm bánh Macaron hoàn hảo.</p>',
                'slug' => 'workshop-lam-banh-macaron',
                'thumbnail' => 'courses/workshop-macaron.webp',
                'price' => 1800000,
                'compare_price' => 2500000,
                'duration_hours' => 16,
                'level' => 'intermediate',
                'status' => 'active',
                'is_featured' => false,
                'order' => 6,
                'max_students' => 15,
                'requirements' => json_encode(['Có kiến thức làm bánh cơ bản']),
                'what_you_learn' => json_encode(['Làm Macaron hoàn hảo', 'Kỹ thuật tạo vỏ bánh mịn màng']),
                'gg_form' => 'https://forms.gle/workshop-macaron',
                'group_link' => 'https://zalo.me/g/macaron-group',
                'show_form_link' => true,
                'show_group_link' => true,
                'show_instructor' => true,
                'show_price' => true,
                'cat_course_id' => $categories['workshop'] ?? null,
                'instructor_id' => $instructors['minh-anh'] ?? null,
            ],
            [
                'title' => 'Bánh Á truyền thống và hiện đại',
                'description' => '<p>Khóa học chuyên sâu về bánh Á từ truyền thống đến hiện đại, kết hợp tinh hoa ẩm thực châu Á.</p>',
                'slug' => 'banh-a-truyen-thong-va-hien-dai',
                'thumbnail' => 'courses/banh-a-truyen-thong.webp',
                'price' => 2400000,
                'compare_price' => 3200000,
                'duration_hours' => 40,
                'level' => 'intermediate',
                'status' => 'active',
                'is_featured' => false,
                'order' => 7,
                'max_students' => 20,
                'requirements' => json_encode(['Thành thạo làm bánh cơ bản', 'Hiểu về văn hóa ẩm thực Á']),
                'what_you_learn' => json_encode(['Làm bánh Á truyền thống', 'Biến tấu hiện đại', 'Kỹ thuật đặc biệt']),
                'gg_form' => 'https://forms.gle/banh-a-truyen-thong',
                'group_link' => 'https://zalo.me/g/banh-a-group',
                'show_form_link' => true,
                'show_group_link' => true,
                'show_instructor' => true,
                'show_price' => true,
                'cat_course_id' => $categories['banh-nang-cao'] ?? null,
                'instructor_id' => $instructors['duc-minh'] ?? null,
            ],
            [
                'title' => 'Kinh doanh bánh từ A đến Z',
                'description' => '<p>Khóa học toàn diện về kinh doanh bánh, từ lập kế hoạch đến vận hành và phát triển thương hiệu.</p>',
                'slug' => 'kinh-doanh-banh-tu-a-den-z',
                'thumbnail' => 'courses/kinh-doanh-banh.webp',
                'price' => 2800000,
                'compare_price' => 3800000,
                'duration_hours' => 32,
                'level' => 'advanced',
                'status' => 'active',
                'is_featured' => true,
                'order' => 8,
                'max_students' => 25,
                'requirements' => json_encode(['Có kinh nghiệm làm bánh', 'Mong muốn kinh doanh']),
                'what_you_learn' => json_encode(['Lập kế hoạch kinh doanh bánh', 'Marketing và branding', 'Quản lý tài chính']),
                'gg_form' => 'https://forms.gle/kinh-doanh-banh',
                'group_link' => 'https://zalo.me/g/kinh-doanh-banh-group',
                'show_form_link' => true,
                'show_group_link' => true,
                'show_instructor' => true,
                'show_price' => true,
                'cat_course_id' => $categories['workshop'] ?? null,
                'instructor_id' => $instructors['vu-phuc'] ?? null,
            ],
        ];

        foreach ($courses as $courseData) {
            Course::updateOrCreate(
                ['slug' => $courseData['slug']],
                $courseData
            );
        }

        $this->command->info("✅ Đã tạo " . count($courses) . " khóa học");
    }
}
