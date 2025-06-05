<?php

namespace Database\Seeders;

use App\Models\Testimonial;
use Illuminate\Database\Seeder;

class TestimonialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $testimonials = [
            [
                'name' => 'Nguyễn Văn An',
                'position' => 'Chủ tiệm bánh',
                'company' => 'Tiệm bánh Hương Vị',
                'location' => 'TP.HCM',
                'content' => 'VBA Vũ Phúc không chỉ là nhà cung cấp nguyên liệu mà còn là đối tác tuyệt vời trong việc hỗ trợ kỹ thuật. Đội ngũ chuyên gia của họ luôn sẵn sàng giải đáp mọi thắc mắc và đưa ra những giải pháp phù hợp.',
                'rating' => 5,
                'order' => 1,
                'status' => 'active',
            ],
            [
                'name' => 'Trần Thị Bình',
                'position' => 'Đầu bếp bánh',
                'company' => null,
                'location' => 'Cần Thơ',
                'content' => 'Các khóa đào tạo của VBA Vũ Phúc đã giúp tôi nâng cao kỹ năng làm bánh một cách đáng kinh ngạc. Họ không chỉ cung cấp kiến thức chất lượng mà còn chia sẻ những bí quyết giúp kinh doanh thành công hơn.',
                'rating' => 5,
                'order' => 2,
                'status' => 'active',
            ],
            [
                'name' => 'Lê Văn Cường',
                'position' => 'Quản lý nhà hàng',
                'company' => 'Nhà hàng Sài Gòn',
                'location' => 'Vĩnh Long',
                'content' => 'Nhờ có sự tư vấn từ VBA Vũ Phúc, nhà hàng của tôi đã tiết kiệm được đáng kể chi phí và thời gian trong việc chuẩn bị các món tráng miệng. Sản phẩm của họ luôn đảm bảo chất lượng và dịch vụ giao hàng rất đáng tin cậy.',
                'rating' => 4,
                'order' => 3,
                'status' => 'active',
            ],
            [
                'name' => 'Phạm Thị Dung',
                'position' => 'Chủ tiệm bánh mới',
                'company' => 'Bánh ngọt Dung',
                'location' => 'Long An',
                'content' => 'Là một đầu bếp bánh mới bước vào nghề, tôi rất biết ơn VBA Vũ Phúc vì đã định hướng nghề nghiệp và cung cấp những khóa đào tạo chất lượng cao. Sự hỗ trợ của họ đã giúp tôi xây dựng được thương hiệu riêng.',
                'rating' => 5,
                'order' => 4,
                'status' => 'active',
            ],
            [
                'name' => 'Hoàng Văn Em',
                'position' => 'Giám đốc chuỗi bánh',
                'company' => 'Chuỗi bánh An Giang',
                'location' => 'An Giang',
                'content' => 'Nói về dịch vụ khách hàng, VBA Vũ Phúc luôn đứng đầu. Họ không chỉ cung cấp sản phẩm chất lượng mà còn tư vấn tận tâm giúp doanh nghiệp nhỏ như chúng tôi tiết kiệm chi phí và tối ưu hóa sản xuất.',
                'rating' => 5,
                'order' => 5,
                'status' => 'active',
            ],
        ];

        foreach ($testimonials as $testimonial) {
            Testimonial::create($testimonial);
        }
    }
}
