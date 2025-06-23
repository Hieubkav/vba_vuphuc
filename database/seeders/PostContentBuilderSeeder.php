<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\CatPost;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PostContentBuilderSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        // Tạo category nếu chưa có
        $category = CatPost::firstOrCreate([
            'name' => 'Bài viết mẫu',
            'slug' => 'bai-viet-mau',
        ], [
            'description' => 'Danh mục chứa các bài viết mẫu với nội dung đa dạng',
            'status' => 'active',
            'order' => 1,
        ]);

        // Tạo bài viết với content builder
        $posts = [
            [
                'title' => 'Hướng dẫn sử dụng Content Builder',
                'content_builder' => [
                    [
                        'type' => 'heading',
                        'data' => [
                            'level' => 'h2',
                            'text' => 'Giới thiệu về Content Builder'
                        ]
                    ],
                    [
                        'type' => 'paragraph',
                        'data' => [
                            'content' => '<p>Content Builder là một công cụ mạnh mẽ giúp bạn tạo ra những bài viết với nội dung đa dạng và phong phú. Với hệ thống blocks linh hoạt, bạn có thể dễ dàng kết hợp text, hình ảnh, video, audio và nhiều loại nội dung khác.</p>'
                        ]
                    ],
                    [
                        'type' => 'quote',
                        'data' => [
                            'content' => 'Sự đơn giản là sự tinh tế tối thượng.',
                            'author' => 'Leonardo da Vinci',
                            'style' => 'highlight'
                        ]
                    ],
                    [
                        'type' => 'heading',
                        'data' => [
                            'level' => 'h3',
                            'text' => 'Các loại blocks có sẵn'
                        ]
                    ],
                    [
                        'type' => 'list',
                        'data' => [
                            'type' => 'bullet',
                            'title' => 'Danh sách các blocks:',
                            'items' => "📝 Paragraph - Đoạn văn với rich text\n📋 Heading - Tiêu đề các cấp độ\n🖼️ Image - Hình ảnh với caption\n🖼️ Gallery - Thư viện ảnh\n🎥 Video - YouTube, Vimeo, upload\n🎵 Audio - File âm thanh\n💬 Quote - Trích dẫn\n💻 Code - Mã nguồn\n📋 List - Danh sách\n➖ Divider - Đường phân cách\n🎯 CTA - Call to Action"
                        ]
                    ],
                    [
                        'type' => 'divider',
                        'data' => [
                            'style' => 'gradient',
                            'thickness' => 'medium',
                            'spacing' => 'large'
                        ]
                    ],
                    [
                        'type' => 'cta',
                        'data' => [
                            'title' => 'Bắt đầu tạo nội dung ngay!',
                            'description' => 'Khám phá sức mạnh của Content Builder và tạo ra những bài viết ấn tượng.',
                            'button_text' => 'Tạo bài viết mới',
                            'button_url' => '/admin/posts/create',
                            'style' => 'primary',
                            'size' => 'large'
                        ]
                    ]
                ],
                'excerpt' => 'Tìm hiểu cách sử dụng Content Builder để tạo ra những bài viết với nội dung đa dạng và phong phú.',
                'is_featured' => true
            ],
            [
                'title' => 'Mẹo tối ưu hóa hình ảnh cho web',
                'content_builder' => [
                    [
                        'type' => 'paragraph',
                        'data' => [
                            'content' => '<p>Hình ảnh đóng vai trò quan trọng trong việc thu hút người đọc và cải thiện trải nghiệm người dùng. Tuy nhiên, nếu không được tối ưu hóa đúng cách, chúng có thể làm chậm tốc độ tải trang.</p>'
                        ]
                    ],
                    [
                        'type' => 'heading',
                        'data' => [
                            'level' => 'h2',
                            'text' => 'Các định dạng hình ảnh phổ biến'
                        ]
                    ],
                    [
                        'type' => 'list',
                        'data' => [
                            'type' => 'checklist',
                            'items' => "WebP - Định dạng hiện đại, dung lượng nhỏ\nJPEG - Phù hợp cho ảnh có nhiều màu sắc\nPNG - Hỗ trợ trong suốt, chất lượng cao\nSVG - Vector, phù hợp cho icon và logo"
                        ]
                    ],
                    [
                        'type' => 'code',
                        'data' => [
                            'language' => 'html',
                            'title' => 'Responsive Image HTML',
                            'content' => '<img src="image.webp" \n     alt="Mô tả ảnh" \n     loading="lazy"\n     width="800" \n     height="600"\n     class="responsive-image">',
                            'line_numbers' => true
                        ]
                    ],
                    [
                        'type' => 'quote',
                        'data' => [
                            'content' => 'Một hình ảnh đáng giá hơn ngàn từ, nhưng chỉ khi nó được tối ưu hóa đúng cách.',
                            'author' => 'Web Developer',
                            'style' => 'minimal'
                        ]
                    ]
                ],
                'excerpt' => 'Học cách tối ưu hóa hình ảnh để cải thiện tốc độ tải trang và trải nghiệm người dùng.'
            ],
            [
                'title' => 'Xu hướng thiết kế web 2024',
                'content_builder' => [
                    [
                        'type' => 'heading',
                        'data' => [
                            'level' => 'h2',
                            'text' => 'Những xu hướng đáng chú ý'
                        ]
                    ],
                    [
                        'type' => 'paragraph',
                        'data' => [
                            'content' => '<p>Năm 2024 mang đến nhiều xu hướng thiết kế web mới, từ minimalism đến dark mode, từ micro-interactions đến AI-powered design.</p>'
                        ]
                    ],
                    [
                        'type' => 'list',
                        'data' => [
                            'type' => 'numbered',
                            'title' => 'Top 5 xu hướng thiết kế web 2024:',
                            'items' => "Minimalist Design - Thiết kế tối giản\nDark Mode - Chế độ tối\nMicro-interactions - Tương tác nhỏ\nAI Integration - Tích hợp AI\nSustainable Design - Thiết kế bền vững"
                        ]
                    ],
                    [
                        'type' => 'divider',
                        'data' => [
                            'style' => 'dashed',
                            'thickness' => 'thin',
                            'spacing' => 'medium'
                        ]
                    ],
                    [
                        'type' => 'paragraph',
                        'data' => [
                            'content' => '<p>Việc theo kịp các xu hướng này sẽ giúp website của bạn luôn hiện đại và thu hút người dùng.</p>'
                        ]
                    ]
                ],
                'excerpt' => 'Khám phá những xu hướng thiết kế web hot nhất năm 2024 và cách áp dụng chúng vào dự án của bạn.'
            ]
        ];

        foreach ($posts as $postData) {
            $post = Post::create([
                'title' => $postData['title'],
                'slug' => Str::slug($postData['title']),
                'content' => '', // Để trống vì sử dụng content_builder
                'content_builder' => $postData['content_builder'],
                'excerpt' => $postData['excerpt'],
                'category_id' => $category->id,
                'status' => 'active',
                'is_featured' => $postData['is_featured'] ?? false,
                'published_at' => now(),
                'order' => 0,
            ]);

            $this->command->info("Created post: {$post->title}");
        }

        $this->command->info('✅ Post Content Builder seeder completed!');
    }
}
