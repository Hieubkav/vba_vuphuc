<?php

namespace Database\Seeders;

use App\Models\Faq;
use Illuminate\Database\Seeder;

class FaqSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faqs = [
            [
                'question' => 'VBA Vũ Phúc cung cấp những khóa học gì?',
                'answer' => '<p>VBA Vũ Phúc cung cấp đa dạng các khóa học về:</p>
                <ul>
                    <li><strong>Kỹ năng làm bánh cơ bản:</strong> Bánh bông lan, bánh kem, bánh quy</li>
                    <li><strong>Kỹ thuật nâng cao:</strong> Bánh fondant, bánh cưới, bánh nghệ thuật</li>
                    <li><strong>Hội thảo chuyên đề:</strong> Quản lý kinh doanh tiệm bánh, marketing</li>
                    <li><strong>Khóa học online:</strong> Học từ xa với video hướng dẫn chi tiết</li>
                </ul>',
                'category' => 'courses',
                'order' => 1,
                'status' => 'active',
            ],
            [
                'question' => 'Quy trình đăng ký khóa học như thế nào?',
                'answer' => '<p>Để đăng ký khóa học tại VBA Vũ Phúc, bạn có thể:</p>
                <ul>
                    <li>Đăng ký trực tiếp qua Google Form trên website</li>
                    <li>Liên hệ qua Zalo hoặc Facebook để được tư vấn</li>
                    <li>Gọi điện thoại để đăng ký và được hỗ trợ</li>
                    <li>Đến trực tiếp trung tâm để đăng ký và tham quan cơ sở</li>
                </ul>
                <p>Sau khi đăng ký, bạn sẽ nhận được thông tin chi tiết về lịch học và chuẩn bị.</p>',
                'category' => 'courses',
                'order' => 2,
                'status' => 'active',
            ],
            [
                'question' => 'Làm thế nào để tham gia nhóm học tập?',
                'answer' => '<p>VBA Vũ Phúc có các nhóm học tập trên Facebook và Zalo để học viên:</p>
                <ul>
                    <li>Chia sẻ kinh nghiệm và thành quả học tập</li>
                    <li>Được hỗ trợ kỹ thuật từ giảng viên</li>
                    <li>Kết nối với cộng đồng học viên</li>
                    <li>Cập nhật thông tin khóa học mới</li>
                </ul>
                <p>Thông tin tham gia nhóm sẽ được cung cấp sau khi đăng ký khóa học.</p>',
                'category' => 'courses',
                'order' => 3,
                'status' => 'active',
            ],
            [
                'question' => 'Tài liệu khóa học có được cung cấp không?',
                'answer' => '<p>Mỗi khóa học tại VBA Vũ Phúc đều có tài liệu đi kèm:</p>
                <ul>
                    <li><strong>Tài liệu mở:</strong> Có thể xem và tải về miễn phí</li>
                    <li><strong>Tài liệu dành cho học viên:</strong> Chỉ học viên đã đăng ký mới được truy cập</li>
                    <li><strong>Video hướng dẫn:</strong> Ghi lại toàn bộ quá trình thực hành</li>
                    <li><strong>Album ảnh:</strong> Hình ảnh chi tiết từng bước thực hiện</li>
                </ul>',
                'category' => 'courses',
                'order' => 4,
                'status' => 'active',
            ],
            [
                'question' => 'Làm thế nào để được hỗ trợ kỹ thuật?',
                'answer' => '<p>VBA Vũ Phúc cam kết hỗ trợ kỹ thuật trọn đời cho học viên:</p>
                <ul>
                    <li>Hỗ trợ qua nhóm Facebook và Zalo</li>
                    <li>Tư vấn trực tiếp với giảng viên</li>
                    <li>Hỗ trợ giải quyết vấn đề kỹ thuật trong quá trình thực hành</li>
                    <li>Tư vấn kinh doanh và phát triển thương hiệu</li>
                </ul>',
                'category' => 'support',
                'order' => 5,
                'status' => 'active',
            ],
            [
                'question' => 'Chi phí học tại VBA Vũ Phúc như thế nào?',
                'answer' => '<p>Chi phí khóa học tại VBA Vũ Phúc được tính theo:</p>
                <ul>
                    <li><strong>Khóa học cơ bản:</strong> Từ 500.000đ - 1.500.000đ</li>
                    <li><strong>Khóa học nâng cao:</strong> Từ 1.500.000đ - 3.000.000đ</li>
                    <li><strong>Khóa học chuyên sâu:</strong> Từ 3.000.000đ trở lên</li>
                </ul>
                <p>Học viên cũ và đăng ký nhóm sẽ được ưu đãi đặc biệt.</p>',
                'category' => 'general',
                'order' => 6,
                'status' => 'active',
            ],
        ];

        foreach ($faqs as $faq) {
            Faq::create($faq);
        }
    }
}
