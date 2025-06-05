<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Post;
use App\Models\CatPost;
use Illuminate\Support\Str;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('📝 Tạo dữ liệu bài viết...');

        $categories = CatPost::pluck('id', 'slug')->toArray();

        $posts = [
            // Tin tức
            [
                'title' => 'VBA Vũ Phúc ra mắt khóa học làm bánh chuyên nghiệp 2024',
                'content' => $this->getNewsContent1(),
                'slug' => 'vba-vu-phuc-ra-mat-khoa-hoc-lam-banh-2024',
                'thumbnail' => 'posts/news-baking-course-2024.webp',
                'is_featured' => true,
                'type' => 'news',
                'category_id' => $categories['tin-tuc'] ?? null,
                'status' => 'active',
                'order' => 1,
            ],
            [
                'title' => 'Xu hướng bánh ngọt 2024 - Những điều cần biết',
                'content' => $this->getNewsContent2(),
                'slug' => 'xu-huong-banh-ngot-2024',
                'thumbnail' => 'posts/baking-trends-2024.webp',
                'is_featured' => true,
                'type' => 'news',
                'category_id' => $categories['tin-tuc'] ?? null,
                'status' => 'active',
                'order' => 2,
            ],
            [
                'title' => 'Ngành làm bánh Việt Nam phát triển mạnh mẽ',
                'content' => $this->getNewsContent3(),
                'slug' => 'nganh-lam-banh-viet-nam-phat-trien',
                'thumbnail' => 'posts/vietnam-baking-industry.webp',
                'is_featured' => false,
                'type' => 'news',
                'category_id' => $categories['tin-tuc'] ?? null,
                'status' => 'active',
                'order' => 3,
            ],

            // Hướng dẫn
            [
                'title' => 'Hướng dẫn làm bánh bông lan cơ bản cho người mới',
                'content' => $this->getTutorialContent1(),
                'slug' => 'huong-dan-lam-banh-bong-lan-co-ban',
                'thumbnail' => 'posts/banh-bong-lan-tutorial.webp',
                'is_featured' => true,
                'type' => 'normal',
                'category_id' => $categories['huong-dan'] ?? null,
                'status' => 'active',
                'order' => 4,
            ],
            [
                'title' => 'Cách làm kem tươi hoàn hảo tại nhà',
                'content' => $this->getTutorialContent2(),
                'slug' => 'cach-lam-kem-tuoi-hoan-hao',
                'thumbnail' => 'posts/kem-tuoi-tutorial.webp',
                'is_featured' => false,
                'type' => 'normal',
                'category_id' => $categories['huong-dan'] ?? null,
                'status' => 'active',
                'order' => 5,
            ],
            [
                'title' => 'Kỹ thuật trang trí bánh với kem bơ',
                'content' => $this->getTutorialContent3(),
                'slug' => 'ky-thuat-trang-tri-banh-kem-bo',
                'thumbnail' => 'posts/trang-tri-banh-kem-bo.webp',
                'is_featured' => false,
                'type' => 'normal',
                'category_id' => $categories['huong-dan'] ?? null,
                'status' => 'active',
                'order' => 6,
            ],

            // Kinh nghiệm
            [
                'title' => '10 mẹo hay khi học làm bánh',
                'content' => $this->getExperienceContent1(),
                'slug' => '10-meo-hay-khi-hoc-lam-banh',
                'thumbnail' => 'posts/10-tips-baking.webp',
                'is_featured' => true,
                'type' => 'normal',
                'category_id' => $categories['kinh-nghiem'] ?? null,
                'status' => 'active',
                'order' => 7,
            ],
            [
                'title' => 'Những lỗi thường gặp khi làm bánh và cách khắc phục',
                'content' => $this->getExperienceContent2(),
                'slug' => 'loi-thuong-gap-lam-banh-cach-khac-phuc',
                'thumbnail' => 'posts/common-baking-mistakes.webp',
                'is_featured' => false,
                'type' => 'normal',
                'category_id' => $categories['kinh-nghiem'] ?? null,
                'status' => 'active',
                'order' => 8,
            ],

            // Công thức bánh
            [
                'title' => 'Công thức bánh Tiramisu chuẩn Ý',
                'content' => $this->getRecipeContent1(),
                'slug' => 'cong-thuc-banh-tiramisu-chuan-y',
                'thumbnail' => 'posts/tiramisu-recipe.webp',
                'is_featured' => true,
                'type' => 'normal',
                'category_id' => $categories['cong-thuc-banh'] ?? null,
                'status' => 'active',
                'order' => 9,
            ],
            [
                'title' => 'Cách làm bánh Macaron Pháp hoàn hảo',
                'content' => $this->getRecipeContent2(),
                'slug' => 'cach-lam-banh-macaron-phap-hoan-hao',
                'thumbnail' => 'posts/macaron-recipe.webp',
                'is_featured' => false,
                'type' => 'normal',
                'category_id' => $categories['cong-thuc-banh'] ?? null,
                'status' => 'active',
                'order' => 10,
            ],

            // Thêm bài viết
            [
                'title' => 'Bí quyết chọn nguyên liệu làm bánh chất lượng',
                'content' => $this->getTutorialContent4(),
                'slug' => 'bi-quyet-chon-nguyen-lieu-lam-banh',
                'thumbnail' => 'posts/chon-nguyen-lieu-banh.webp',
                'is_featured' => false,
                'type' => 'normal',
                'category_id' => $categories['meo-lam-banh'] ?? null,
                'status' => 'active',
                'order' => 11,
            ],
            [
                'title' => 'Xu hướng trang trí bánh 2024',
                'content' => $this->getNewsContent4(),
                'slug' => 'xu-huong-trang-tri-banh-2024',
                'thumbnail' => 'posts/trang-tri-banh-2024.webp',
                'is_featured' => true,
                'type' => 'news',
                'category_id' => $categories['tin-tuc'] ?? null,
                'status' => 'active',
                'order' => 12,
            ],
            [
                'title' => 'Công thức bánh Croissant bơ thơm ngon',
                'content' => $this->getRecipeContent3(),
                'slug' => 'cong-thuc-banh-croissant-bo-thom-ngon',
                'thumbnail' => 'posts/croissant-recipe.webp',
                'is_featured' => false,
                'type' => 'normal',
                'category_id' => $categories['cong-thuc-banh'] ?? null,
                'status' => 'active',
                'order' => 13,
            ],
            [
                'title' => 'Kỹ thuật làm bánh chiffon mềm mịn',
                'content' => $this->getTutorialContent5(),
                'slug' => 'ky-thuat-lam-banh-chiffon-mem-min',
                'thumbnail' => 'posts/banh-chiffon-tutorial.webp',
                'is_featured' => false,
                'type' => 'normal',
                'category_id' => $categories['huong-dan'] ?? null,
                'status' => 'active',
                'order' => 14,
            ],
            [
                'title' => 'Kinh nghiệm học làm bánh từ con số 0',
                'content' => $this->getExperienceContent3(),
                'slug' => 'kinh-nghiem-hoc-lam-banh-tu-con-so-0',
                'thumbnail' => 'posts/learn-baking-from-zero.webp',
                'is_featured' => true,
                'type' => 'normal',
                'category_id' => $categories['kinh-nghiem'] ?? null,
                'status' => 'active',
                'order' => 15,
            ],
        ];

        foreach ($posts as $postData) {
            // Tự động tạo SEO nếu chưa có
            if (empty($postData['seo_title'])) {
                $postData['seo_title'] = $postData['title'] . ' - VBA Vũ Phúc';
            }
            if (empty($postData['seo_description'])) {
                $postData['seo_description'] = Str::limit(strip_tags($postData['content']), 155);
            }

            Post::updateOrCreate(
                ['slug' => $postData['slug']],
                $postData
            );
        }

        $this->command->info("✅ Đã tạo " . count($posts) . " bài viết");
    }

    private function getNewsContent1(): string
    {
        return '<h2>Khóa học làm bánh chuyên nghiệp 2024 - Nâng tầm kỹ năng của bạn</h2>
        <p>VBA Vũ Phúc tự hào giới thiệu khóa học làm bánh chuyên nghiệp 2024 với nhiều cải tiến và nội dung mới, phù hợp với xu hướng làm bánh hiện đại.</p>

        <h3>Điểm nổi bật của khóa học:</h3>
        <ul>
            <li>Nội dung được cập nhật theo xu hướng bánh 2024</li>
            <li>Thêm nhiều công thức bánh quốc tế</li>
            <li>Hỗ trợ học viên 24/7</li>
            <li>Chứng chỉ được công nhận trong ngành</li>
        </ul>

        <p>Đăng ký ngay để nhận ưu đãi đặc biệt cho 100 học viên đầu tiên!</p>';
    }

    private function getNewsContent2(): string
    {
        return '<h2>Xu hướng bánh ngọt 2024 - Những điều cần biết</h2>
        <p>Năm 2024 đánh dấu nhiều xu hướng mới trong ngành bánh ngọt với sự kết hợp giữa truyền thống và hiện đại.</p>

        <h3>Xu hướng nổi bật:</h3>
        <ul>
            <li>Bánh healthy với nguyên liệu tự nhiên</li>
            <li>Trang trí bánh minimalist</li>
            <li>Bánh fusion Á - Âu</li>
            <li>Công nghệ mới trong làm bánh</li>
        </ul>';
    }

    private function getNewsContent3(): string
    {
        return '<h2>Ngành làm bánh Việt Nam phát triển mạnh mẽ</h2>
        <p>Năm 2024 đánh dấu sự phát triển vượt bậc của ngành làm bánh tại Việt Nam với nhiều cơ hội mới.</p>

        <h3>Những điểm tích cực:</h3>
        <ul>
            <li>Nhu cầu bánh ngọt tăng cao</li>
            <li>Nhiều thương hiệu bánh nổi tiếng</li>
            <li>Đầu tư vào công nghệ làm bánh</li>
            <li>Xuất khẩu bánh ra thế giới</li>
        </ul>';
    }

    private function getTutorialContent1(): string
    {
        return '<h2>Hướng dẫn làm bánh bông lan cơ bản</h2>
        <p>Bánh bông lan là loại bánh cơ bản nhất mà ai học làm bánh cũng nên bắt đầu. Với kỹ thuật đơn giản nhưng cần sự tỉ mỉ.</p>

        <h3>Bước 1: Chuẩn bị nguyên liệu</h3>
        <p>Trứng gà, đường, bột mì, bơ lạt và một chút muối...</p>

        <h3>Bước 2: Đánh trứng và đường</h3>
        <p>Đánh trứng với đường cho đến khi hỗn hợp trắng xốp và gấp đôi thể tích...</p>';
    }

    private function getTutorialContent2(): string
    {
        return '<h2>Cách làm kem tươi hoàn hảo</h2>
        <p>Kem tươi là thành phần quan trọng trong trang trí bánh. Làm kem tươi đúng cách sẽ giúp bánh thêm hấp dẫn.</p>

        <h3>Cách làm kem tươi:</h3>
        <ol>
            <li>Chuẩn bị whipping cream lạnh</li>
            <li>Đánh kem với tốc độ thấp</li>
            <li>Tăng dần tốc độ đánh</li>
            <li>Dừng khi kem đạt độ sệt vừa phải</li>
        </ol>';
    }

    private function getTutorialContent3(): string
    {
        return '<h2>Kỹ thuật trang trí bánh với kem bơ</h2>
        <p>Kem bơ là loại kem phổ biến để trang trí bánh với nhiều kỹ thuật khác nhau.</p>

        <h3>Các bước trang trí cơ bản:</h3>
        <ol>
            <li>Chuẩn bị kem bơ mịn</li>
            <li>Chọn túi bắt kem phù hợp</li>
            <li>Luyện tập các động tác cơ bản</li>
            <li>Trang trí theo ý tưởng sáng tạo</li>
        </ol>';
    }

    private function getExperienceContent1(): string
    {
        return '<h2>10 mẹo hay khi học làm bánh</h2>
        <p>Dựa trên kinh nghiệm giảng dạy nhiều năm, đây là những mẹo hữu ích cho người học làm bánh:</p>

        <ol>
            <li>Luôn cân đo nguyên liệu chính xác</li>
            <li>Chuẩn bị đầy đủ dụng cụ trước khi bắt đầu</li>
            <li>Kiểm tra nhiệt độ lò nướng</li>
            <li>Thực hành thường xuyên với các công thức cơ bản</li>
            <li>Ghi chép lại những điều học được</li>
        </ol>';
    }

    private function getExperienceContent2(): string
    {
        return '<h2>Những lỗi thường gặp khi làm bánh</h2>
        <p>Những lỗi phổ biến và cách khắc phục khi làm bánh:</p>

        <h3>1. Bánh bị xẹp sau khi nướng</h3>
        <p>Nguyên nhân: Nhiệt độ lò không đều hoặc mở lò quá sớm...</p>

        <h3>2. Bánh bị khô và cứng</h3>
        <p>Nguyên nhân: Nướng quá lâu hoặc nhiệt độ quá cao...</p>';
    }

    private function getRecipeContent1(): string
    {
        return '<h2>Công thức bánh Tiramisu chuẩn Ý</h2>
        <p>Tiramisu là món bánh ngọt nổi tiếng của Ý với hương vị cà phê đặc trưng và kem mascarpone mềm mịn.</p>

        <h3>Nguyên liệu:</h3>
        <ul>
            <li>Bánh ladyfinger: 200g</li>
            <li>Kem mascarpone: 500g</li>
            <li>Cà phê espresso: 300ml</li>
            <li>Bột cacao: để rắc</li>
        </ul>';
    }

    private function getRecipeContent2(): string
    {
        return '<h2>Cách làm bánh Macaron Pháp hoàn hảo</h2>
        <p>Macaron là loại bánh ngọt tinh tế của Pháp với vỏ bánh mịn màng và nhân kem thơm ngon.</p>

        <h3>Nguyên liệu:</h3>
        <ul>
            <li>Bột hạnh nhân: 100g</li>
            <li>Đường bột: 200g</li>
            <li>Lòng trắng trứng: 75g</li>
            <li>Màu thực phẩm: vài giọt</li>
        </ul>';
    }

    private function getTutorialContent4(): string
    {
        return '<h2>Bí quyết chọn nguyên liệu làm bánh chất lượng</h2>
        <p>Việc chọn nguyên liệu chất lượng là yếu tố quyết định thành công của một chiếc bánh ngon.</p>

        <h3>Nguyên tắc chọn nguyên liệu:</h3>
        <ol>
            <li>Chọn bột mì có độ gluten phù hợp</li>
            <li>Sử dụng trứng tươi, không mùi tanh</li>
            <li>Bơ lạt chất lượng cao</li>
            <li>Kiểm tra hạn sử dụng của nguyên liệu</li>
        </ol>';
    }

    private function getNewsContent4(): string
    {
        return '<h2>Xu hướng trang trí bánh 2024</h2>
        <p>Những xu hướng mới trong trang trí bánh năm 2024 với phong cách hiện đại và sáng tạo.</p>

        <h3>Xu hướng nổi bật:</h3>
        <ul>
            <li>Trang trí minimalist và tinh tế</li>
            <li>Sử dụng màu sắc tự nhiên</li>
            <li>Kỹ thuật drip cake</li>
            <li>Trang trí với hoa tươi</li>
        </ul>';
    }

    private function getRecipeContent3(): string
    {
        return '<h2>Công thức bánh Croissant bơ thơm ngon</h2>
        <p>Croissant là loại bánh nướng nổi tiếng của Pháp với lớp vỏ giòn và ruột mềm, thơm mùi bơ.</p>

        <h3>Nguyên liệu:</h3>
        <ul>
            <li>Bột mì bread flour: 500g</li>
            <li>Bơ lạt: 300g</li>
            <li>Sữa tươi: 250ml</li>
            <li>Men nướng bánh: 10g</li>
            <li>Đường: 50g</li>
        </ul>';
    }

    private function getTutorialContent5(): string
    {
        return '<h2>Kỹ thuật làm bánh chiffon mềm mịn</h2>
        <p>Bánh chiffon là loại bánh có kết cấu mềm mịn, xốp nhẹ với hương vị thơm ngon đặc trưng.</p>

        <h3>Kỹ thuật quan trọng:</h3>
        <ol>
            <li>Tách lòng trắng và lòng đỏ trứng</li>
            <li>Đánh lòng trắng đến độ sệt vừa phải</li>
            <li>Trộn bột theo kỹ thuật gấp nhẹ</li>
            <li>Nướng ở nhiệt độ thấp trong thời gian dài</li>
        </ol>';
    }

    private function getExperienceContent3(): string
    {
        return '<h2>Kinh nghiệm học làm bánh từ con số 0</h2>
        <p>Chia sẻ kinh nghiệm học làm bánh từ con số 0 đến thành thạo, dành cho những ai mới bắt đầu.</p>

        <h3>Lộ trình học tập:</h3>
        <ol>
            <li>Nắm vững các kỹ thuật cơ bản</li>
            <li>Thực hành với các loại bánh đơn giản</li>
            <li>Học các kỹ thuật trang trí</li>
            <li>Thực hành với dự án bánh phức tạp</li>
            <li>Tham gia cộng đồng làm bánh</li>
        </ol>';
    }
}
