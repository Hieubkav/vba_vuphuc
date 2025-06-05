<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Instructor;

class InstructorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('👨‍🏫 Tạo dữ liệu giảng viên...');

        $instructors = [
            [
                'name' => 'Thầy Vũ Phúc',
                'email' => 'vuphuc@vbavuphuc.com',
                'phone' => '0123456789',
                'bio' => 'Chuyên gia làm bánh với hơn 15 năm kinh nghiệm trong lĩnh vực đào tạo và tư vấn. Đã giúp hàng nghìn học viên từ người mới bắt đầu đến chuyên nghiệp nâng cao kỹ năng làm bánh.',
                'specialization' => 'Bánh Âu, Bánh Á, Trang trí bánh',
                'experience_years' => 15,
                'education' => 'Cử nhân Công nghệ Thực phẩm - Đại học Bách Khoa TP.HCM, Chứng chỉ Pastry Arts - Le Cordon Bleu',
                'certifications' => [
                    [
                        'name' => 'Pastry Arts Professional Certificate',
                        'issuer' => 'Le Cordon Bleu',
                        'date' => '2018-06-15'
                    ],
                    [
                        'name' => 'Advanced Cake Decoration Certificate',
                        'issuer' => 'Wilton School',
                        'date' => '2019-03-20'
                    ]
                ],
                'achievements' => 'Tác giả của 3 cuốn sách về làm bánh. Giải nhất cuộc thi Pastry Chef toàn quốc 2020. Diễn giả tại hơn 80 hội thảo về nghệ thuật làm bánh.',
                'teaching_philosophy' => 'Làm bánh không chỉ là kỹ thuật mà còn là nghệ thuật. Tôi tin rằng ai cũng có thể tạo ra những chiếc bánh tuyệt vời nếu được hướng dẫn đúng cách và có đam mê.',
                'hourly_rate' => 800000,
                'social_links' => [
                    [
                        'platform' => 'youtube',
                        'url' => 'https://youtube.com/@vbavuphuc'
                    ],
                    [
                        'platform' => 'facebook',
                        'url' => 'https://facebook.com/vbavuphuc'
                    ]
                ],
                'status' => 'active',
                'order' => 1,
            ],
            [
                'name' => 'Cô Minh Anh',
                'email' => 'minhanh@vbavuphuc.com',
                'phone' => '0987654321',
                'bio' => 'Chuyên gia làm bánh Pháp với kinh nghiệm sâu về các loại bánh ngọt cổ điển. Chuyên dạy các khóa học bánh Âu và kỹ thuật trang trí bánh tinh tế.',
                'specialization' => 'Bánh Pháp, Bánh ngọt cổ điển, Trang trí bánh',
                'experience_years' => 12,
                'education' => 'Cử nhân Công nghệ Thực phẩm - Đại học Nông Lâm TP.HCM, Chứng chỉ French Pastry - Institut Paul Bocuse',
                'certifications' => [
                    [
                        'name' => 'French Pastry Professional Certificate',
                        'issuer' => 'Institut Paul Bocuse',
                        'date' => '2017-09-10'
                    ]
                ],
                'achievements' => 'Đào tạo thành công hơn 1500 học viên làm bánh. Giải nhì cuộc thi French Pastry Championship 2019.',
                'teaching_philosophy' => 'Bánh Pháp không chỉ là món ăn, mà là nghệ thuật tinh tế cần sự tỉ mỉ và đam mê.',
                'hourly_rate' => 700000,
                'social_links' => [
                    [
                        'platform' => 'instagram',
                        'url' => 'https://instagram.com/minhanh_pastry'
                    ]
                ],
                'status' => 'active',
                'order' => 2,
            ],
            [
                'name' => 'Thầy Đức Minh',
                'email' => 'ducminh@vbavuphuc.com',
                'phone' => '0369852147',
                'bio' => 'Chuyên gia về bánh Á và bánh truyền thống Việt Nam. Có kinh nghiệm làm việc tại các khách sạn 5 sao và hiện tại tập trung vào đào tạo.',
                'specialization' => 'Bánh Á, Bánh truyền thống, Bánh mì',
                'experience_years' => 10,
                'education' => 'Cử nhân Quản trị Khách sạn - Đại học Hoa Sen, Chứng chỉ Asian Pastry - Singapore Culinary Institute',
                'certifications' => [
                    [
                        'name' => 'Asian Pastry Professional Certificate',
                        'issuer' => 'Singapore Culinary Institute',
                        'date' => '2019-11-15'
                    ]
                ],
                'achievements' => 'Thiết kế menu bánh cho hơn 30 khách sạn và nhà hàng cao cấp. Giải ba cuộc thi Asian Pastry Championship 2021.',
                'teaching_philosophy' => 'Bánh truyền thống là di sản văn hóa, cần được bảo tồn và phát triển với kỹ thuật hiện đại.',
                'hourly_rate' => 650000,
                'social_links' => [
                    [
                        'platform' => 'facebook',
                        'url' => 'https://facebook.com/ducminh.pastry'
                    ]
                ],
                'status' => 'active',
                'order' => 3,
            ]
        ];

        foreach ($instructors as $instructorData) {
            // Tạo slug từ tên
            $instructorData['slug'] = \Illuminate\Support\Str::slug($instructorData['name']);

            Instructor::updateOrCreate(
                ['email' => $instructorData['email']],
                $instructorData
            );
        }

        $this->command->info("✅ Đã tạo " . count($instructors) . " giảng viên");
    }
}
