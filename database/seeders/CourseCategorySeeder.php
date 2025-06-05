<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\CatPost;
use App\Models\Course;

class CourseCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Tạo 3 danh mục khóa học chính
        $categories = [
            [
                'name' => 'Kỹ năng',
                'slug' => 'ky-nang',
                'description' => 'Các khóa học phát triển kỹ năng cá nhân và nghề nghiệp',
                'seo_title' => 'Khóa học Kỹ năng - VBA Vũ Phúc',
                'seo_description' => 'Phát triển kỹ năng cá nhân và nghề nghiệp với các khóa học chất lượng cao tại VBA Vũ Phúc',
                'status' => 'active',
                'order' => 1
            ],
            [
                'name' => 'Kỹ thuật',
                'slug' => 'ky-thuat',
                'description' => 'Các khóa học kỹ thuật chuyên sâu và ứng dụng thực tế',
                'seo_title' => 'Khóa học Kỹ thuật - VBA Vũ Phúc',
                'seo_description' => 'Nâng cao kiến thức kỹ thuật với các khóa học chuyên sâu tại VBA Vũ Phúc',
                'status' => 'active',
                'order' => 2
            ],
            [
                'name' => 'Hội thảo',
                'slug' => 'hoi-thao',
                'description' => 'Các buổi hội thảo, workshop và chia sẻ kinh nghiệm',
                'seo_title' => 'Hội thảo - VBA Vũ Phúc',
                'seo_description' => 'Tham gia các buổi hội thảo và workshop bổ ích tại VBA Vũ Phúc',
                'status' => 'active',
                'order' => 3
            ]
        ];

        $createdCategories = [];
        foreach ($categories as $categoryData) {
            $category = CatPost::firstOrCreate([
                'slug' => $categoryData['slug']
            ], $categoryData);
            
            $createdCategories[$categoryData['slug']] = $category;
        }

        // Tạo khóa học mẫu cho từng danh mục
        $courses = [
            // Khóa học Kỹ năng
            [
                'title' => 'Kỹ năng giao tiếp và thuyết trình hiệu quả',
                'description' => '<p>Khóa học giúp bạn phát triển kỹ năng giao tiếp và thuyết trình một cách chuyên nghiệp và tự tin.</p>
                    <h3>Nội dung khóa học:</h3>
                    <ul>
                        <li>Kỹ thuật giao tiếp hiệu quả trong công việc</li>
                        <li>Cách xây dựng bài thuyết trình ấn tượng</li>
                        <li>Kỹ năng thuyết phục và đàm phán</li>
                        <li>Quản lý cảm xúc khi nói trước đám đông</li>
                        <li>Sử dụng ngôn ngữ cơ thể hiệu quả</li>
                    </ul>',
                'slug' => 'ky-nang-giao-tiep-va-thuyet-trinh-hieu-qua',
                'price' => 1500000,
                'compare_price' => 2000000,
                'duration_hours' => 20,
                'level' => 'beginner',
                'status' => 'active',
                'is_featured' => true,
                'order' => 1,
                'max_students' => 30,
                'start_date' => '2024-07-15',
                'end_date' => '2024-08-15',
                'requirements' => [
                    ['requirement' => 'Không yêu cầu kinh nghiệm trước đó'],
                    ['requirement' => 'Mong muốn cải thiện kỹ năng giao tiếp']
                ],
                'what_you_learn' => [
                    ['learning_outcome' => 'Giao tiếp tự tin và hiệu quả'],
                    ['learning_outcome' => 'Thuyết trình chuyên nghiệp'],
                    ['learning_outcome' => 'Kỹ năng thuyết phục người khác'],
                    ['learning_outcome' => 'Quản lý stress khi nói trước đám đông']
                ],
                'instructor_name' => 'Thầy Minh Tuấn',
                'instructor_bio' => 'Chuyên gia đào tạo kỹ năng mềm với 10 năm kinh nghiệm.',
                'thumbnail' => 'courses/ky-nang-giao-tiep.jpg',
                'gg_form' => 'https://forms.gle/example-ky-nang-giao-tiep',
                'group_link' => 'https://zalo.me/g/example-ky-nang',
                'show_form_link' => true,
                'show_group_link' => true,
                'category_slug' => 'ky-nang'
            ],
            
            // Khóa học Kỹ thuật
            [
                'title' => 'Excel VBA nâng cao - Tự động hóa công việc',
                'description' => '<p>Khóa học Excel VBA nâng cao giúp bạn tự động hóa các công việc phức tạp và tăng hiệu suất làm việc.</p>
                    <h3>Nội dung khóa học:</h3>
                    <ul>
                        <li>Lập trình VBA từ cơ bản đến nâng cao</li>
                        <li>Tạo UserForm và xử lý sự kiện</li>
                        <li>Kết nối và xử lý dữ liệu từ database</li>
                        <li>Tối ưu hóa và debug code VBA</li>
                        <li>Xây dựng ứng dụng quản lý hoàn chỉnh</li>
                    </ul>',
                'slug' => 'excel-vba-nang-cao-tu-dong-hoa-cong-viec',
                'price' => 2500000,
                'compare_price' => 3500000,
                'duration_hours' => 40,
                'level' => 'intermediate',
                'status' => 'active',
                'is_featured' => true,
                'order' => 1,
                'max_students' => 25,
                'start_date' => '2024-07-20',
                'end_date' => '2024-09-20',
                'requirements' => [
                    ['requirement' => 'Có kiến thức cơ bản về Excel'],
                    ['requirement' => 'Hiểu biết về lập trình cơ bản']
                ],
                'what_you_learn' => [
                    ['learning_outcome' => 'Lập trình VBA thành thạo'],
                    ['learning_outcome' => 'Tự động hóa các tác vụ phức tạp'],
                    ['learning_outcome' => 'Xây dựng ứng dụng quản lý'],
                    ['learning_outcome' => 'Tối ưu hóa hiệu suất làm việc']
                ],
                'instructor_name' => 'Thầy Vũ Phúc',
                'instructor_bio' => 'Chuyên gia Excel VBA với hơn 15 năm kinh nghiệm trong lĩnh vực đào tạo.',
                'thumbnail' => 'courses/excel-vba-nang-cao.jpg',
                'gg_form' => 'https://forms.gle/example-excel-vba-nang-cao',
                'group_link' => 'https://zalo.me/g/example-vba',
                'show_form_link' => true,
                'show_group_link' => true,
                'category_slug' => 'ky-thuat'
            ],
            
            // Hội thảo
            [
                'title' => 'Hội thảo: Xu hướng công nghệ 2024 và cơ hội nghề nghiệp',
                'description' => '<p>Buổi hội thảo chia sẻ về xu hướng công nghệ mới nhất và các cơ hội nghề nghiệp trong năm 2024.</p>
                    <h3>Nội dung hội thảo:</h3>
                    <ul>
                        <li>Xu hướng công nghệ nổi bật năm 2024</li>
                        <li>AI và Machine Learning trong công việc</li>
                        <li>Cơ hội nghề nghiệp trong lĩnh vực IT</li>
                        <li>Kỹ năng cần thiết cho tương lai</li>
                        <li>Q&A với các chuyên gia</li>
                    </ul>',
                'slug' => 'hoi-thao-xu-huong-cong-nghe-2024-va-co-hoi-nghe-nghiep',
                'price' => 0,
                'compare_price' => null,
                'duration_hours' => 3,
                'level' => 'beginner',
                'status' => 'active',
                'is_featured' => true,
                'order' => 1,
                'max_students' => 100,
                'start_date' => '2024-07-10',
                'end_date' => '2024-07-10',
                'requirements' => [
                    ['requirement' => 'Quan tâm đến công nghệ và phát triển nghề nghiệp']
                ],
                'what_you_learn' => [
                    ['learning_outcome' => 'Hiểu về xu hướng công nghệ mới'],
                    ['learning_outcome' => 'Nắm bắt cơ hội nghề nghiệp'],
                    ['learning_outcome' => 'Kết nối với các chuyên gia'],
                    ['learning_outcome' => 'Định hướng phát triển bản thân']
                ],
                'instructor_name' => 'Ban tổ chức VBA Vũ Phúc',
                'instructor_bio' => 'Đội ngũ chuyên gia và giảng viên giàu kinh nghiệm.',
                'thumbnail' => 'courses/hoi-thao-xu-huong-2024.jpg',
                'gg_form' => 'https://forms.gle/example-hoi-thao-2024',
                'group_link' => 'https://zalo.me/g/example-hoi-thao',
                'show_form_link' => true,
                'show_group_link' => true,
                'category_slug' => 'hoi-thao'
            ]
        ];

        foreach ($courses as $courseData) {
            $categorySlug = $courseData['category_slug'];
            unset($courseData['category_slug']);
            
            $courseData['category_id'] = $createdCategories[$categorySlug]->id;
            
            Course::firstOrCreate([
                'slug' => $courseData['slug']
            ], $courseData);
        }

        $this->command->info('Đã tạo thành công 3 danh mục và ' . count($courses) . ' khóa học mẫu!');
    }
}
