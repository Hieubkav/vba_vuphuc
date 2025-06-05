<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CourseGroup;

class CourseGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $courseGroups = [
            [
                'name' => 'Nhóm học làm bánh cơ bản',
                'slug' => 'nhom-hoc-lam-banh-co-ban',
                'description' => 'Nhóm học tập dành cho người mới bắt đầu với làm bánh. Chia sẻ kinh nghiệm, hỏi đáp và hỗ trợ lẫn nhau trong quá trình học.',
                'level' => 'beginner',
                'max_members' => 50,
                'current_members' => 23,
                'group_link' => 'https://www.facebook.com/groups/lambanh coban',
                'group_type' => 'facebook',
                'instructor_name' => 'Vũ Phúc',
                'instructor_bio' => 'Chuyên gia làm bánh với hơn 15 năm kinh nghiệm',
                'color' => '#1877f2',
                'icon' => 'fab fa-facebook',
                'status' => 'active',
                'is_featured' => true,
                'order' => 1,
            ],
            [
                'name' => 'Cộng đồng bánh Pháp nâng cao',
                'slug' => 'cong-dong-banh-phap-nang-cao',
                'description' => 'Nhóm dành cho những người đã có kinh nghiệm với bánh Pháp, thảo luận các kỹ thuật nâng cao và chia sẻ công thức.',
                'level' => 'advanced',
                'max_members' => 30,
                'current_members' => 18,
                'group_link' => 'https://zalo.me/g/banhphapnangcao',
                'group_type' => 'zalo',
                'instructor_name' => 'Minh Anh',
                'instructor_bio' => 'Chuyên gia bánh Pháp với hơn 12 năm kinh nghiệm',
                'color' => '#0068ff',
                'icon' => 'fas fa-comments',
                'status' => 'active',
                'is_featured' => true,
                'order' => 2,
            ],
            [
                'name' => 'Nhóm trang trí bánh nghệ thuật',
                'slug' => 'nhom-trang-tri-banh-nghe-thuat',
                'description' => 'Chuyên về trang trí bánh nghệ thuật và sáng tạo. Chia sẻ kỹ thuật và ý tưởng trang trí độc đáo.',
                'level' => 'intermediate',
                'max_members' => 40,
                'current_members' => 32,
                'group_link' => 'https://www.facebook.com/groups/trangtribanhnghe thuat',
                'group_type' => 'facebook',
                'instructor_name' => 'Vũ Phúc',
                'instructor_bio' => 'Chuyên gia trang trí bánh với hơn 15 năm kinh nghiệm',
                'color' => '#1877f2',
                'icon' => 'fab fa-facebook',
                'status' => 'active',
                'is_featured' => true,
                'order' => 3,
            ],
            [
                'name' => 'Bánh Á truyền thống',
                'slug' => 'banh-a-truyen-thong',
                'description' => 'Nhóm chuyên biệt về bánh Á truyền thống như bánh chưng, bánh tét, bánh dày và các loại bánh dân gian.',
                'level' => 'intermediate',
                'max_members' => 35,
                'current_members' => 28,
                'group_link' => 'https://zalo.me/g/banhatruyen thong',
                'group_type' => 'zalo',
                'instructor_name' => 'Đức Minh',
                'instructor_bio' => 'Chuyên gia bánh Á với hơn 10 năm kinh nghiệm',
                'color' => '#0068ff',
                'icon' => 'fas fa-comments',
                'status' => 'active',
                'is_featured' => true,
                'order' => 4,
            ],
            [
                'name' => 'Kinh doanh bánh thành công',
                'slug' => 'kinh-doanh-banh-thanh-cong',
                'description' => 'Học cách kinh doanh bánh hiệu quả, từ lập kế hoạch đến marketing và phát triển thương hiệu.',
                'level' => 'advanced',
                'max_members' => 25,
                'current_members' => 15,
                'group_link' => 'https://www.facebook.com/groups/kinhdoanhbanh',
                'group_type' => 'facebook',
                'instructor_name' => 'Vũ Phúc',
                'instructor_bio' => 'Chuyên gia kinh doanh bánh với hơn 15 năm kinh nghiệm',
                'color' => '#1877f2',
                'icon' => 'fab fa-facebook',
                'status' => 'active',
                'is_featured' => true,
                'order' => 5,
            ],
            [
                'name' => 'Bánh healthy và dinh dưỡng',
                'slug' => 'banh-healthy-va-dinh-duong',
                'description' => 'Tạo ra những chiếc bánh healthy với nguyên liệu tự nhiên, ít đường và giàu dinh dưỡng.',
                'level' => 'intermediate',
                'max_members' => 30,
                'current_members' => 22,
                'group_link' => 'https://zalo.me/g/banhhealthy',
                'group_type' => 'zalo',
                'instructor_name' => 'Minh Anh',
                'instructor_bio' => 'Chuyên gia bánh healthy với hơn 12 năm kinh nghiệm',
                'color' => '#0068ff',
                'icon' => 'fas fa-comments',
                'status' => 'active',
                'is_featured' => true,
                'order' => 6,
            ],
        ];

        foreach ($courseGroups as $courseData) {
            CourseGroup::create($courseData);
        }

        $this->command->info('Đã tạo ' . count($courseGroups) . ' nhóm khóa học!');
    }
}
