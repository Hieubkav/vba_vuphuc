<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Setting;
use App\Models\CatPost;
use App\Models\Post;
use App\Models\PostImage;
use App\Models\Partner;
use App\Models\Association;
use App\Models\Slider;
use App\Models\MenuItem;
use Illuminate\Database\Seeder;

class FirstSeed extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Tạo User admin
        $this->createUsers();

        // 2. Tạo Settings
        $this->createSettings();

        // 3. Tạo Categories
        $catPosts = $this->createCatPosts();

        // 4. Tạo Posts và PostImages
        $this->createPosts($catPosts);

        // 6. Tạo Partners
        $this->createPartners();

        // 7. Tạo Associations
        $this->createAssociations();

        // 9. Tạo Sliders
        $this->createSliders();

        // 11. Tạo MenuItems
        $this->createMenuItems($catPosts);

        // 12. Gọi các seeder khác
        $this->call([
            PostNewsSeeder::class,
            PostServiceSeeder::class,
            PostCourseSeeder::class,
        ]);
    }

    private function createUsers()
    {
        User::create([
            'name' => 'Admin',
            'email' => 'admin@vuphucbaking.com',
            'password' => 'password',
            'order' => 1,
            'status' => 'active',
        ]);

        User::create([
            'name' => 'Editor',
            'email' => 'editor@vuphucbaking.com',
            'password' => 'password',
            'order' => 2,
            'status' => 'active',
        ]);
    }

    private function createSettings()
    {
        Setting::create([
            'site_name' => 'Vũ Phúc Bakery',
            'logo_link' => '/images/logo.png',
            'favicon_link' => '/images/favicon.ico',
            'seo_title' => 'Vũ Phúc Bakery - Bánh ngon mỗi ngày',
            'seo_description' => 'Vũ Phúc Bakery chuyên sản xuất và cung cấp các loại bánh tươi ngon, chất lượng cao với nguyên liệu tự nhiên.',
            'og_image_link' => '/images/og-image.jpg',
            'hotline' => '0123456789',
            'address' => '123 Đường ABC, Quận XYZ, TP.HCM',
            'email' => 'info@vuphucbaking.com',
            'slogan' => 'Bánh ngon mỗi ngày',
            'facebook_link' => 'https://facebook.com/vuphucbaking',
            'zalo_link' => 'https://zalo.me/vuphucbaking',
            'youtube_link' => 'https://youtube.com/vuphucbaking',
            'tiktok_link' => 'https://tiktok.com/@vuphucbaking',
            'working_hours' => '6:00 - 22:00 (Thứ 2 - Chủ nhật)',
            'order' => 1,
            'status' => 'active',
        ]);
    }



    private function createCatPosts()
    {
        $categories = [
            [
                'name' => 'Tin Tức',
                'slug' => 'tin-tuc',
                'seo_title' => 'Tin Tức - Vũ Phúc Bakery',
                'seo_description' => 'Cập nhật tin tức mới nhất về sản phẩm và hoạt động của Vũ Phúc Bakery.',
                'image' => '/images/categories/tin-tuc.jpg',
                'description' => 'Tin tức và cập nhật mới nhất',
                'order' => 1,
                'status' => 'active',
            ],
            [
                'name' => 'Hướng Dẫn',
                'slug' => 'huong-dan',
                'seo_title' => 'Hướng Dẫn Làm Bánh - Vũ Phúc Bakery',
                'seo_description' => 'Hướng dẫn chi tiết cách làm bánh tại nhà với các công thức đơn giản.',
                'image' => '/images/categories/huong-dan.jpg',
                'description' => 'Hướng dẫn làm bánh tại nhà',
                'order' => 2,
                'status' => 'active',
            ],
            [
                'name' => 'Khuyến Mãi',
                'slug' => 'khuyen-mai',
                'seo_title' => 'Khuyến Mãi - Vũ Phúc Bakery',
                'seo_description' => 'Thông tin về các chương trình khuyến mãi hấp dẫn tại Vũ Phúc Bakery.',
                'image' => '/images/categories/khuyen-mai.jpg',
                'description' => 'Chương trình khuyến mãi hấp dẫn',
                'order' => 3,
                'status' => 'active',
            ],
        ];

        $createdCategories = [];
        foreach ($categories as $category) {
            $createdCategories[] = CatPost::create($category);
        }

        return $createdCategories;
    }



    private function createPosts($catPosts)
    {
        $posts = [
            [
                'title' => 'Chào mừng đến với Vũ Phúc Bakery',
                'content' => '<p>Vũ Phúc Bakery tự hào là một trong những tiệm bánh hàng đầu với nhiều năm kinh nghiệm trong ngành. Chúng tôi cam kết mang đến cho khách hàng những sản phẩm bánh tươi ngon nhất.</p><p>Với đội ngũ thợ làm bánh chuyên nghiệp và nguyên liệu chất lượng cao, mỗi chiếc bánh tại Vũ Phúc đều được chế biến tỉ mỉ và đầy tâm huyết.</p>',
                'seo_title' => 'Chào mừng đến với Vũ Phúc Bakery - Tiệm bánh uy tín',
                'seo_description' => 'Vũ Phúc Bakery - tiệm bánh uy tín với nhiều năm kinh nghiệm, chuyên cung cấp các loại bánh tươi ngon chất lượng cao.',
                'slug' => 'chao-mung-den-voi-vu-phuc-bakery',
                'thumbnail' => '/images/posts/chao-mung.jpg',
                'is_featured' => true,
                'type' => 'news',
                'order' => 1,
                'status' => 'active',
                'category_id' => $catPosts[0]->id,
            ],
            [
                'title' => 'Hướng dẫn làm bánh mì tại nhà',
                'content' => '<p>Bánh mì là món ăn quen thuộc của người Việt. Hôm nay chúng tôi sẽ hướng dẫn bạn cách làm bánh mì thơm ngon tại nhà.</p><h3>Nguyên liệu cần chuẩn bị:</h3><ul><li>Bột mì: 500g</li><li>Men nướng: 7g</li><li>Muối: 10g</li><li>Đường: 12g</li><li>Nước: 320ml</li></ul><h3>Cách làm:</h3><p>Trộn tất cả nguyên liệu khô, sau đó thêm nước từ từ và nhào bột trong 10-15 phút...</p>',
                'seo_title' => 'Hướng dẫn làm bánh mì tại nhà đơn giản',
                'seo_description' => 'Học cách làm bánh mì tại nhà với công thức đơn giản, dễ thực hiện từ Vũ Phúc Bakery.',
                'slug' => 'huong-dan-lam-banh-mi-tai-nha',
                'thumbnail' => '/images/posts/huong-dan-banh-mi.jpg',
                'is_featured' => false,
                'type' => 'normal',
                'order' => 2,
                'status' => 'active',
                'category_id' => $catPosts[1]->id,
            ],
        ];

        foreach ($posts as $postData) {
            $post = Post::create($postData);

            // Tạo ảnh cho bài viết
            PostImage::create([
                'post_id' => $post->id,
                'image_link' => '/images/posts/' . $post->slug . '-1.jpg',
                'alt_text' => $post->title,
                'order' => 1,
                'status' => 'active',
            ]);
        }
    }





    private function createPartners()
    {
        $partners = [
            [
                'name' => 'Microsoft Vietnam',
                'logo_link' => '/images/partners/microsoft.jpg',
                'website_link' => 'https://microsoft.com/vi-vn',
                'description' => 'Đối tác công nghệ hàng đầu cung cấp giải pháp Office và Azure',
                'order' => 1,
                'status' => 'active',
            ],
            [
                'name' => 'FPT Software',
                'logo_link' => '/images/partners/fpt.jpg',
                'website_link' => 'https://fpt-software.com',
                'description' => 'Công ty phần mềm hàng đầu Việt Nam',
                'order' => 2,
                'status' => 'active',
            ],
            [
                'name' => 'Vietcombank',
                'logo_link' => '/images/partners/vcb.jpg',
                'website_link' => 'https://vietcombank.com.vn',
                'description' => 'Ngân hàng thương mại cổ phần Ngoại thương Việt Nam',
                'order' => 3,
                'status' => 'active',
            ],
            [
                'name' => 'KPMG Vietnam',
                'logo_link' => '/images/partners/kpmg.jpg',
                'website_link' => 'https://kpmg.com.vn',
                'description' => 'Công ty kiểm toán và tư vấn hàng đầu',
                'order' => 4,
                'status' => 'active',
            ],
            [
                'name' => 'Deloitte Vietnam',
                'logo_link' => '/images/partners/deloitte.jpg',
                'website_link' => 'https://deloitte.com/vn',
                'description' => 'Dịch vụ tư vấn và kiểm toán chuyên nghiệp',
                'order' => 5,
                'status' => 'active',
            ],
            [
                'name' => 'PwC Vietnam',
                'logo_link' => '/images/partners/pwc.jpg',
                'website_link' => 'https://pwc.com/vn',
                'description' => 'Dịch vụ tư vấn thuế và kiểm toán',
                'order' => 6,
                'status' => 'active',
            ],
        ];

        foreach ($partners as $partnerData) {
            Partner::create($partnerData);
        }
    }
    private function createAssociations()
    {
        Association::create([
            'name' => 'Hiệp hội Bánh kẹo Việt Nam',
            'image_link' => '/images/associations/banh-keo-vn.jpg',
            'description' => 'Thành viên của Hiệp hội Bánh kẹo Việt Nam',
            'website_link' => 'https://vietbakers.org',
            'order' => 1,
            'status' => 'active',
        ]);
    }



    private function createSliders()
    {
        Slider::create([
            'image_link' => '/images/sliders/slider-1.jpg',
            'title' => 'Bánh tươi ngon mỗi ngày',
            'description' => 'Chuyên cung cấp bánh tươi ngon chất lượng cao',
            'link' => '/san-pham',
            'alt_text' => 'Bánh tươi ngon tại Vũ Phúc Bakery',
            'order' => 1,
            'status' => true,
        ]);

        Slider::create([
            'image_link' => '/images/sliders/slider-2.jpg',
            'title' => 'Bánh kem sinh nhật đặc biệt',
            'description' => 'Đặt bánh kem sinh nhật theo yêu cầu với nhiều mẫu mã đẹp mắt',
            'link' => '/banh-kem',
            'alt_text' => 'Bánh kem sinh nhật đẹp mắt',
            'order' => 2,
            'status' => true,
        ]);
    }

    private function createMenuItems($catPosts)
    {
        // Menu chính
        MenuItem::create([
            'label' => 'Trang chủ',
            'type' => 'link',
            'link' => '/',
            'order' => 1,
            'status' => 'active',
        ]);

        MenuItem::create([
            'label' => 'Khóa học',
            'type' => 'link',
            'link' => '/khoa-hoc',
            'order' => 2,
            'status' => 'active',
        ]);

        MenuItem::create([
            'label' => 'Tin tức',
            'type' => 'cat_post',
            'cat_post_id' => $catPosts[0]->id,
            'order' => 3,
            'status' => 'active',
        ]);

        MenuItem::create([
            'label' => 'Liên hệ',
            'type' => 'link',
            'link' => '/lien-he',
            'order' => 4,
            'status' => 'active',
        ]);
    }
}