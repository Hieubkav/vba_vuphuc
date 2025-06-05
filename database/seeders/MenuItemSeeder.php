<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MenuItem;

class MenuItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Xóa dữ liệu cũ
        MenuItem::truncate();

        // Tạo menu cấp 1: Trang chủ
        MenuItem::create([
            'label' => 'Trang chủ',
            'type' => 'link',
            'link' => '/',
            'order' => 1,
            'status' => 'active'
        ]);

        // Tạo menu cấp 1: Giới thiệu
        $gioiThieu = MenuItem::create([
            'label' => 'Giới thiệu',
            'type' => 'display_only',
            'link' => '#',
            'order' => 2,
            'status' => 'active'
        ]);

        // Tạo menu cấp 2 cho Giới thiệu
        MenuItem::create([
            'parent_id' => $gioiThieu->id,
            'label' => 'Về VBA Vũ Phúc',
            'type' => 'link',
            'link' => '/gioi-thieu',
            'order' => 1,
            'status' => 'active'
        ]);

        MenuItem::create([
            'parent_id' => $gioiThieu->id,
            'label' => 'Đội ngũ giảng viên',
            'type' => 'link',
            'link' => '/giang-vien',
            'order' => 2,
            'status' => 'active'
        ]);

        MenuItem::create([
            'parent_id' => $gioiThieu->id,
            'label' => 'Tầm nhìn & Sứ mệnh',
            'type' => 'link',
            'link' => '/tam-nhin-su-menh',
            'order' => 3,
            'status' => 'active'
        ]);

        // Tạo menu cấp 1: Khóa học
        $khoaHoc = MenuItem::create([
            'label' => 'Khóa học',
            'type' => 'link',
            'link' => '/khoa-hoc',
            'order' => 3,
            'status' => 'active'
        ]);

        // Tạo menu cấp 2 cho Khóa học (theo danh mục)
        $categories = \App\Models\CatPost::where('status', 'active')
            ->whereHas('courses', function($query) {
                $query->where('status', 'active');
            })
            ->orderBy('order')
            ->get();

        foreach ($categories as $index => $category) {
            MenuItem::create([
                'parent_id' => $khoaHoc->id,
                'label' => $category->name,
                'type' => 'cat_post',
                'cat_post_id' => $category->id,
                'order' => $index + 1,
                'status' => 'active'
            ]);
        }

        // Tạo menu cấp 1: Đăng ký học viên
        MenuItem::create([
            'label' => 'Đăng ký học viên',
            'type' => 'link',
            'link' => '/dang-ky-hoc-vien',
            'order' => 4,
            'status' => 'active'
        ]);

        // Tạo menu cấp 1: Tin tức & Bài viết
        $tinTuc = MenuItem::create([
            'label' => 'Tin tức',
            'type' => 'link',
            'link' => '/tin-tuc',
            'order' => 5,
            'status' => 'active'
        ]);

        // Tạo menu cấp 2 cho Tin tức
        MenuItem::create([
            'parent_id' => $tinTuc->id,
            'label' => 'Tin tức & Sự kiện',
            'type' => 'link',
            'link' => '/tin-tuc/su-kien',
            'order' => 1,
            'status' => 'active'
        ]);

        MenuItem::create([
            'parent_id' => $tinTuc->id,
            'label' => 'Kinh nghiệm học tập',
            'type' => 'link',
            'link' => '/tin-tuc/kinh-nghiem',
            'order' => 2,
            'status' => 'active'
        ]);

        MenuItem::create([
            'parent_id' => $tinTuc->id,
            'label' => 'Mẹo làm bánh',
            'type' => 'link',
            'link' => '/tin-tuc/meo-lam-banh',
            'order' => 3,
            'status' => 'active'
        ]);

        // Tạo menu cấp 1: Hỗ trợ
        $hoTro = MenuItem::create([
            'label' => 'Hỗ trợ',
            'type' => 'display_only',
            'link' => '#',
            'order' => 6,
            'status' => 'active'
        ]);

        // Tạo menu cấp 2 cho Hỗ trợ
        MenuItem::create([
            'parent_id' => $hoTro->id,
            'label' => 'Câu hỏi thường gặp',
            'type' => 'link',
            'link' => '/faq',
            'order' => 1,
            'status' => 'active'
        ]);

        MenuItem::create([
            'parent_id' => $hoTro->id,
            'label' => 'Hướng dẫn thanh toán',
            'type' => 'link',
            'link' => '/huong-dan-thanh-toan',
            'order' => 2,
            'status' => 'active'
        ]);

        MenuItem::create([
            'parent_id' => $hoTro->id,
            'label' => 'Chính sách học viên',
            'type' => 'link',
            'link' => '/chinh-sach-hoc-vien',
            'order' => 3,
            'status' => 'active'
        ]);

        // Tạo menu cấp 1: Liên hệ
        MenuItem::create([
            'label' => 'Liên hệ',
            'type' => 'link',
            'link' => '/lien-he',
            'order' => 7,
            'status' => 'active'
        ]);

        $this->command->info("✅ Đã tạo " . MenuItem::count() . " menu items cho website khóa học");
        $this->command->info("📋 Cấu trúc menu:");

        $parentMenus = MenuItem::whereNull('parent_id')->orderBy('order')->get();
        foreach ($parentMenus as $parent) {
            $this->command->info("  🔸 {$parent->label}");
            foreach ($parent->children as $child) {
                $this->command->info("    └── {$child->label}");
            }
        }
    }
}
