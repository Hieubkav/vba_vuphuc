<?php

namespace App\Filament\Admin\Pages;

use App\Models\WebDesign;
use App\Models\Post;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Builder;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Cache;

class ManageWebDesign extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = null;

    protected static ?string $navigationGroup = 'Hệ Thống';

    protected static string $view = 'filament.admin.pages.manage-web-design';

    protected static ?string $title = 'Quản Lý Giao Diện';

    protected static ?string $navigationLabel = 'Quản Lý Giao Diện';

    protected static ?int $navigationSort = 2;

    public ?array $data = [];

    public function mount(): void
    {
        $webDesign = WebDesign::first();

        // Nếu chưa có WebDesign, tạo mới với default values
        if (!$webDesign) {
            $webDesign = WebDesign::create([
                'hero_banner_enabled' => true,
                'hero_banner_order' => 1,
                'courses_overview_enabled' => true,
                'courses_overview_order' => 2,
                'courses_overview_title' => 'Khóa học VBA Excel chuyên nghiệp',
                'courses_overview_description' => 'Nâng cao kỹ năng Excel với các khóa học VBA từ cơ bản đến nâng cao',
                'album_timeline_enabled' => true,
                'album_timeline_order' => 3,
                'album_timeline_title' => 'Timeline khóa học',
                'album_timeline_description' => 'Tài liệu PDF từ các khóa học đã diễn ra',
                'course_groups_enabled' => true,
                'course_groups_order' => 4,
                'course_groups_title' => 'Nhóm học tập',
                'course_groups_description' => 'Tham gia các nhóm Facebook/Zalo để học hỏi và trao đổi kinh nghiệm',
                'course_categories_enabled' => true,
                'course_categories_order' => 5,
                'course_categories_title' => 'Khóa học theo chuyên mục',
                'course_categories_description' => 'Khám phá các khóa học được phân loại theo từng chuyên mục',
                'testimonials_enabled' => true,
                'testimonials_order' => 6,
                'testimonials_title' => 'Đánh giá từ học viên',
                'testimonials_description' => 'Chia sẻ từ những học viên đã tham gia khóa học',
                'faq_enabled' => true,
                'faq_order' => 7,
                'faq_title' => 'Câu hỏi thường gặp',
                'faq_description' => 'Giải đáp những thắc mắc phổ biến về khóa học',
                'partners_enabled' => true,
                'partners_order' => 8,
                'partners_title' => 'Đối tác tin cậy',
                'partners_description' => 'Những đối tác đồng hành cùng chúng tôi',
                'blog_posts_enabled' => true,
                'blog_posts_order' => 9,
                'blog_posts_title' => 'Bài viết mới nhất',
                'blog_posts_description' => 'Cập nhật kiến thức và thông tin hữu ích',
                'footer_enabled' => true,
                'footer_order' => 10,
                'footer_policy_1_title' => 'Chính sách & Điều khoản',
                'footer_policy_1_url' => '#',
                'footer_policy_2_title' => 'Hệ thống đại lý',
                'footer_policy_2_url' => '#',
                'footer_policy_3_title' => 'Bảo mật & Quyền riêng tư',
                'footer_policy_3_url' => '#',
                'footer_copyright' => '© ' . date('Y') . ' Copyright by VBA Vũ Phúc - All Rights Reserved',

            ]);
        }

        // Convert WebDesign model to Builder format
        $sections = $this->convertWebDesignToBuilderFormat($webDesign);

        // Sort by order
        usort($sections, function($a, $b) {
            return $a['data']['order'] <=> $b['data']['order'];
        });

        $this->form->fill(['sections' => $sections]);
    }

    /**
     * Convert WebDesign model to Builder format
     */
    private function convertWebDesignToBuilderFormat($webDesign): array
    {
        return [
            [
                'type' => 'hero_banner',
                'data' => [
                    'enabled' => $webDesign->hero_banner_enabled ?? true,
                    'order' => $webDesign->hero_banner_order ?? 1,
                ]
            ],
            [
                'type' => 'courses_overview',
                'data' => [
                    'enabled' => $webDesign->courses_overview_enabled ?? true,
                    'order' => $webDesign->courses_overview_order ?? 2,
                    'title' => $webDesign->courses_overview_title ?? 'Khóa học chuyên nghiệp',
                    'description' => $webDesign->courses_overview_description ?? 'Khám phá những khóa học được thiết kế bởi các chuyên gia hàng đầu',
                ]
            ],
            [
                'type' => 'album_timeline',
                'data' => [
                    'enabled' => $webDesign->album_timeline_enabled ?? true,
                    'order' => $webDesign->album_timeline_order ?? 3,
                    'title' => $webDesign->album_timeline_title ?? 'Timeline khóa học',
                    'description' => $webDesign->album_timeline_description ?? 'Tài liệu PDF từ các khóa học đã diễn ra',
                ]
            ],
            [
                'type' => 'course_groups',
                'data' => [
                    'enabled' => $webDesign->course_groups_enabled ?? true,
                    'order' => $webDesign->course_groups_order ?? 4,
                    'title' => $webDesign->course_groups_title ?? 'Nhóm học tập',
                    'description' => $webDesign->course_groups_description ?? 'Tham gia các nhóm Facebook/Zalo để học hỏi và trao đổi kinh nghiệm',
                ]
            ],
            [
                'type' => 'course_categories',
                'data' => [
                    'enabled' => $webDesign->course_categories_enabled ?? true,
                    'order' => $webDesign->course_categories_order ?? 5,
                    'title' => $webDesign->course_categories_title ?? 'Khóa học theo chuyên mục',
                    'description' => $webDesign->course_categories_description ?? 'Khám phá các khóa học được phân loại theo từng chuyên mục',
                ]
            ],
            [
                'type' => 'testimonials',
                'data' => [
                    'enabled' => $webDesign->testimonials_enabled ?? true,
                    'order' => $webDesign->testimonials_order ?? 6,
                    'title' => $webDesign->testimonials_title ?? 'Đánh giá từ học viên',
                    'description' => $webDesign->testimonials_description ?? 'Chia sẻ từ những học viên đã tham gia khóa học',
                ]
            ],
            [
                'type' => 'faq',
                'data' => [
                    'enabled' => $webDesign->faq_enabled ?? true,
                    'order' => $webDesign->faq_order ?? 7,
                    'title' => $webDesign->faq_title ?? 'Câu hỏi thường gặp',
                    'description' => $webDesign->faq_description ?? 'Giải đáp những thắc mắc phổ biến về khóa học',
                ]
            ],
            [
                'type' => 'partners',
                'data' => [
                    'enabled' => $webDesign->partners_enabled ?? true,
                    'order' => $webDesign->partners_order ?? 8,
                    'title' => $webDesign->partners_title ?? 'Đối tác tin cậy',
                    'description' => $webDesign->partners_description ?? 'Những đối tác đồng hành cùng chúng tôi',
                ]
            ],
            [
                'type' => 'blog_posts',
                'data' => [
                    'enabled' => $webDesign->blog_posts_enabled ?? true,
                    'order' => $webDesign->blog_posts_order ?? 9,
                    'title' => $webDesign->blog_posts_title ?? 'Bài viết mới nhất',
                    'description' => $webDesign->blog_posts_description ?? 'Cập nhật kiến thức và thông tin hữu ích',
                ]
            ],
            [
                'type' => 'footer',
                'data' => [
                    'enabled' => $webDesign->footer_enabled ?? true,
                    'order' => $webDesign->footer_order ?? 10,
                    'policy_1_title' => $webDesign->footer_policy_1_title ?? 'Chính sách & Điều khoản',
                    'policy_1_type' => $webDesign->footer_policy_1_type ?? 'custom',
                    'policy_1_url' => $webDesign->footer_policy_1_url ?? '#',
                    'policy_1_post' => $webDesign->footer_policy_1_post ?? null,
                    'policy_2_title' => $webDesign->footer_policy_2_title ?? 'Hệ thống đại lý',
                    'policy_2_type' => $webDesign->footer_policy_2_type ?? 'custom',
                    'policy_2_url' => $webDesign->footer_policy_2_url ?? '#',
                    'policy_2_post' => $webDesign->footer_policy_2_post ?? null,
                    'policy_3_title' => $webDesign->footer_policy_3_title ?? 'Bảo mật & Quyền riêng tư',
                    'policy_3_type' => $webDesign->footer_policy_3_type ?? 'custom',
                    'policy_3_url' => $webDesign->footer_policy_3_url ?? '#',
                    'policy_3_post' => $webDesign->footer_policy_3_post ?? null,
                    'copyright' => $webDesign->footer_copyright ?? '© ' . date('Y') . ' Copyright by VBA Vũ Phúc - All Rights Reserved',
                    'company_brand_name' => $webDesign->footer_company_brand_name ?? 'VUPHUC BAKING®',
                    'company_business_license' => $webDesign->footer_company_business_license ?? 'Giấy phép kinh doanh số 1800935879 cấp ngày 29/4/2009',
                    'company_director_info' => $webDesign->footer_company_director_info ?? 'Chịu trách nhiệm nội dung: Trần Uy Vũ - Tổng Giám đốc',
                ]
            ],

        ];
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Quản lý giao diện trang chủ')
                    ->description('Kéo thả để sắp xếp thứ tự các section, bật/tắt hiển thị và tùy chỉnh nội dung')
                    ->schema([
                        Builder::make('sections')
                            ->label('Các phần trang chủ')
                            ->blocks([
                                // Hero Banner Block
                                Builder\Block::make('hero_banner')
                                    ->label('Banner chính')
                                    ->schema([
                                        Grid::make(2)->schema([
                                            Toggle::make('enabled')
                                                ->label('Hiển thị')
                                                ->default(true)
                                                ->inline(false),
                                            Placeholder::make('order_display')
                                                ->label('Thứ tự')
                                                ->content(fn($get) => 'Vị trí: ' . ($get('order') ?? 1))
                                                ->helperText('Kéo thả để thay đổi thứ tự'),
                                        ]),
                                    ])
                                    ->columns(1),

                                // Courses Overview Block
                                Builder\Block::make('courses_overview')
                                    ->label('Giới thiệu khóa học')
                                    ->schema([
                                        Grid::make(2)->schema([
                                            Toggle::make('enabled')
                                                ->label('Hiển thị')
                                                ->default(true)
                                                ->inline(false),
                                            Placeholder::make('order_display')
                                                ->label('Thứ tự')
                                                ->content(fn($get) => 'Vị trí: ' . ($get('order') ?? 2))
                                                ->helperText('Kéo thả để thay đổi thứ tự'),
                                        ]),
                                        TextInput::make('title')
                                            ->label('Tiêu đề')
                                            ->default('Khóa học chuyên nghiệp')
                                            ->maxLength(255),
                                        Textarea::make('description')
                                            ->label('Mô tả')
                                            ->default('Khám phá những khóa học được thiết kế bởi các chuyên gia hàng đầu')
                                            ->rows(2),
                                    ])
                                    ->columns(1),

                                // Album Timeline Block
                                Builder\Block::make('album_timeline')
                                    ->label('Album - Timeline')
                                    ->schema([
                                        Grid::make(2)->schema([
                                            Toggle::make('enabled')
                                                ->label('Hiển thị')
                                                ->default(true)
                                                ->inline(false),
                                            Placeholder::make('order_display')
                                                ->label('Thứ tự')
                                                ->content(fn($get) => 'Vị trí: ' . ($get('order') ?? 3))
                                                ->helperText('Kéo thả để thay đổi thứ tự'),
                                        ]),
                                        TextInput::make('title')
                                            ->label('Tiêu đề')
                                            ->default('Timeline khóa học')
                                            ->maxLength(255),
                                        Textarea::make('description')
                                            ->label('Mô tả')
                                            ->default('Tài liệu PDF từ các khóa học đã diễn ra')
                                            ->rows(2),
                                    ])
                                    ->columns(1),

                                // Course Groups Block
                                Builder\Block::make('course_groups')
                                    ->label('Nhóm học tập')
                                    ->schema([
                                        Grid::make(2)->schema([
                                            Toggle::make('enabled')
                                                ->label('Hiển thị')
                                                ->default(true)
                                                ->inline(false),
                                            TextInput::make('order')
                                                ->label('Thứ tự')
                                                ->numeric()
                                                ->default(4)
                                                ->minValue(1)
                                                ->maxValue(10),
                                        ]),
                                        TextInput::make('title')
                                            ->label('Tiêu đề')
                                            ->default('Nhóm học tập')
                                            ->maxLength(255),
                                        Textarea::make('description')
                                            ->label('Mô tả')
                                            ->default('Tham gia các nhóm Facebook/Zalo để học hỏi và trao đổi kinh nghiệm')
                                            ->rows(2),
                                    ])
                                    ->columns(1),

                                // Course Categories Block
                                Builder\Block::make('course_categories')
                                    ->label('Khóa học theo chuyên mục')
                                    ->schema([
                                        Grid::make(2)->schema([
                                            Toggle::make('enabled')
                                                ->label('Hiển thị')
                                                ->default(true)
                                                ->inline(false),
                                            TextInput::make('order')
                                                ->label('Thứ tự')
                                                ->numeric()
                                                ->default(5)
                                                ->minValue(1)
                                                ->maxValue(10),
                                        ]),
                                        TextInput::make('title')
                                            ->label('Tiêu đề')
                                            ->default('Khóa học theo chuyên mục')
                                            ->maxLength(255),
                                        Textarea::make('description')
                                            ->label('Mô tả')
                                            ->default('Khám phá các khóa học được phân loại theo từng chuyên mục')
                                            ->rows(2),
                                    ])
                                    ->columns(1),

                                // Testimonials Block
                                Builder\Block::make('testimonials')
                                    ->label('Đánh giá từ học viên')
                                    ->schema([
                                        Grid::make(2)->schema([
                                            Toggle::make('enabled')
                                                ->label('Hiển thị')
                                                ->default(true)
                                                ->inline(false),
                                            TextInput::make('order')
                                                ->label('Thứ tự')
                                                ->numeric()
                                                ->default(6)
                                                ->minValue(1)
                                                ->maxValue(10),
                                        ]),
                                        TextInput::make('title')
                                            ->label('Tiêu đề')
                                            ->default('Đánh giá từ học viên')
                                            ->maxLength(255),
                                        Textarea::make('description')
                                            ->label('Mô tả')
                                            ->default('Chia sẻ từ những học viên đã tham gia khóa học')
                                            ->rows(2),
                                    ])
                                    ->columns(1),

                                // FAQ Block
                                Builder\Block::make('faq')
                                    ->label('Câu hỏi thường gặp')
                                    ->schema([
                                        Grid::make(2)->schema([
                                            Toggle::make('enabled')
                                                ->label('Hiển thị')
                                                ->default(true)
                                                ->inline(false),
                                            TextInput::make('order')
                                                ->label('Thứ tự')
                                                ->numeric()
                                                ->default(7)
                                                ->minValue(1)
                                                ->maxValue(10),
                                        ]),
                                        TextInput::make('title')
                                            ->label('Tiêu đề')
                                            ->default('Câu hỏi thường gặp')
                                            ->maxLength(255),
                                        Textarea::make('description')
                                            ->label('Mô tả')
                                            ->default('Giải đáp những thắc mắc phổ biến về khóa học')
                                            ->rows(2),
                                    ])
                                    ->columns(1),

                                // Partners Block
                                Builder\Block::make('partners')
                                    ->label('Đối tác tin cậy')
                                    ->schema([
                                        Grid::make(2)->schema([
                                            Toggle::make('enabled')
                                                ->label('Hiển thị')
                                                ->default(true)
                                                ->inline(false),
                                            TextInput::make('order')
                                                ->label('Thứ tự')
                                                ->numeric()
                                                ->default(8)
                                                ->minValue(1)
                                                ->maxValue(10),
                                        ]),
                                        TextInput::make('title')
                                            ->label('Tiêu đề')
                                            ->default('Đối tác tin cậy')
                                            ->maxLength(255),
                                        Textarea::make('description')
                                            ->label('Mô tả')
                                            ->default('Những đối tác đồng hành cùng chúng tôi')
                                            ->rows(2),
                                    ])
                                    ->columns(1),

                                // Blog Posts Block
                                Builder\Block::make('blog_posts')
                                    ->label('Bài viết mới nhất')
                                    ->schema([
                                        Grid::make(2)->schema([
                                            Toggle::make('enabled')
                                                ->label('Hiển thị')
                                                ->default(true)
                                                ->inline(false),
                                            TextInput::make('order')
                                                ->label('Thứ tự')
                                                ->numeric()
                                                ->default(9)
                                                ->minValue(1)
                                                ->maxValue(10),
                                        ]),
                                        TextInput::make('title')
                                            ->label('Tiêu đề')
                                            ->default('Bài viết mới nhất')
                                            ->maxLength(255),
                                        Textarea::make('description')
                                            ->label('Mô tả')
                                            ->default('Cập nhật kiến thức và thông tin hữu ích')
                                            ->rows(2),
                                    ])
                                    ->columns(1),

                                // Footer Block
                                Builder\Block::make('footer')
                                    ->label('Footer - Chân trang')
                                    ->schema([
                                        Grid::make(2)->schema([
                                            Toggle::make('enabled')
                                                ->label('Hiển thị')
                                                ->default(true)
                                                ->inline(false),
                                            TextInput::make('order')
                                                ->label('Thứ tự')
                                                ->numeric()
                                                ->default(10)
                                                ->minValue(1)
                                                ->maxValue(15),
                                        ]),

                                        Section::make('Chính sách Footer')
                                            ->description('Cấu hình 3 chính sách hiển thị trong footer')
                                            ->schema([
                                                // Policy 1
                                                Grid::make(3)->schema([
                                                    TextInput::make('policy_1_title')
                                                        ->label('Chính sách 1 - Tiêu đề')
                                                        ->default('Chính sách & Điều khoản')
                                                        ->maxLength(255)
                                                        ->columnSpan(1),
                                                    Select::make('policy_1_type')
                                                        ->label('Loại liên kết')
                                                        ->options([
                                                            'post' => 'Chọn bài viết',
                                                            'custom' => 'URL tùy chỉnh',
                                                        ])
                                                        ->default('custom')
                                                        ->live()
                                                        ->columnSpan(1),
                                                    Select::make('policy_1_post')
                                                        ->label('Chọn bài viết')
                                                        ->options(function () {
                                                            return Post::where('status', 'active')
                                                                ->orderBy('title')
                                                                ->pluck('title', 'slug')
                                                                ->toArray();
                                                        })
                                                        ->searchable()
                                                        ->visible(fn($get) => $get('policy_1_type') === 'post')
                                                        ->columnSpan(1),
                                                ]),
                                                TextInput::make('policy_1_url')
                                                    ->label('Chính sách 1 - URL tùy chỉnh')
                                                    ->default('#')
                                                    ->url()
                                                    ->maxLength(255)
                                                    ->visible(fn($get) => $get('policy_1_type') === 'custom'),

                                                // Policy 2
                                                Grid::make(3)->schema([
                                                    TextInput::make('policy_2_title')
                                                        ->label('Chính sách 2 - Tiêu đề')
                                                        ->default('Hệ thống đại lý')
                                                        ->maxLength(255)
                                                        ->columnSpan(1),
                                                    Select::make('policy_2_type')
                                                        ->label('Loại liên kết')
                                                        ->options([
                                                            'post' => 'Chọn bài viết',
                                                            'custom' => 'URL tùy chỉnh',
                                                        ])
                                                        ->default('custom')
                                                        ->live()
                                                        ->columnSpan(1),
                                                    Select::make('policy_2_post')
                                                        ->label('Chọn bài viết')
                                                        ->options(function () {
                                                            return Post::where('status', 'active')
                                                                ->orderBy('title')
                                                                ->pluck('title', 'slug')
                                                                ->toArray();
                                                        })
                                                        ->searchable()
                                                        ->visible(fn($get) => $get('policy_2_type') === 'post')
                                                        ->columnSpan(1),
                                                ]),
                                                TextInput::make('policy_2_url')
                                                    ->label('Chính sách 2 - URL tùy chỉnh')
                                                    ->default('#')
                                                    ->url()
                                                    ->maxLength(255)
                                                    ->visible(fn($get) => $get('policy_2_type') === 'custom'),

                                                // Policy 3
                                                Grid::make(3)->schema([
                                                    TextInput::make('policy_3_title')
                                                        ->label('Chính sách 3 - Tiêu đề')
                                                        ->default('Bảo mật & Quyền riêng tư')
                                                        ->maxLength(255)
                                                        ->columnSpan(1),
                                                    Select::make('policy_3_type')
                                                        ->label('Loại liên kết')
                                                        ->options([
                                                            'post' => 'Chọn bài viết',
                                                            'custom' => 'URL tùy chỉnh',
                                                        ])
                                                        ->default('custom')
                                                        ->live()
                                                        ->columnSpan(1),
                                                    Select::make('policy_3_post')
                                                        ->label('Chọn bài viết')
                                                        ->options(function () {
                                                            return Post::where('status', 'active')
                                                                ->orderBy('title')
                                                                ->pluck('title', 'slug')
                                                                ->toArray();
                                                        })
                                                        ->searchable()
                                                        ->visible(fn($get) => $get('policy_3_type') === 'post')
                                                        ->columnSpan(1),
                                                ]),
                                                TextInput::make('policy_3_url')
                                                    ->label('Chính sách 3 - URL tùy chỉnh')
                                                    ->default('#')
                                                    ->url()
                                                    ->maxLength(255)
                                                    ->visible(fn($get) => $get('policy_3_type') === 'custom'),
                                                Textarea::make('copyright')
                                                    ->label('Copyright')
                                                    ->default('© ' . date('Y') . ' Copyright by VBA Vũ Phúc - All Rights Reserved')
                                                    ->rows(2)
                                                    ->columnSpanFull(),
                                            ])
                                            ->collapsible()
                                            ->collapsed(false),

                                        Section::make('Thông tin công ty')
                                            ->description('Cấu hình thông tin công ty hiển thị trong footer')
                                            ->schema([
                                                TextInput::make('company_brand_name')
                                                    ->label('Tên thương hiệu')
                                                    ->default('VUPHUC BAKING®')
                                                    ->maxLength(255)
                                                    ->helperText('Tên thương hiệu hiển thị trong footer'),
                                                TextInput::make('company_business_license')
                                                    ->label('Giấy phép kinh doanh')
                                                    ->default('Giấy phép kinh doanh số 1800935879 cấp ngày 29/4/2009')
                                                    ->maxLength(255)
                                                    ->helperText('Thông tin giấy phép kinh doanh'),
                                                TextInput::make('company_director_info')
                                                    ->label('Thông tin người chịu trách nhiệm')
                                                    ->default('Chịu trách nhiệm nội dung: Trần Uy Vũ - Tổng Giám đốc')
                                                    ->maxLength(255)
                                                    ->helperText('Thông tin người chịu trách nhiệm nội dung'),
                                            ])
                                            ->collapsible()
                                            ->collapsed(false),
                                    ])
                                    ->columns(1),

                            ])
                            ->collapsible()
                            ->collapsed(true)
                            ->reorderable()
                            ->cloneable(false)
                            ->deletable(false)
                            ->addable(false)
                            ->blockNumbers(false)
                            ->columnSpanFull()

                    ])
            ])
            ->statePath('data');
    }

    /**
     * Create order display field
     */
    private function createOrderDisplay(int $defaultOrder): Placeholder
    {
        return Placeholder::make('order_display')
            ->label('Thứ tự')
            ->content(fn($get) => 'Vị trí: ' . ($get('order') ?? $defaultOrder))
            ->helperText('Kéo thả để thay đổi thứ tự');
    }

    /**
     * Get default sections for Builder
     */
    private function getDefaultSections(): array
    {
        return [
            [
                'type' => 'hero_banner',
                'data' => [
                    'enabled' => true,
                    'order' => 1,
                ]
            ],
            [
                'type' => 'courses_overview',
                'data' => [
                    'enabled' => true,
                    'order' => 2,
                    'title' => 'Khóa học chuyên nghiệp',
                    'description' => 'Khám phá những khóa học được thiết kế bởi các chuyên gia hàng đầu',
                    'bg_color' => 'bg-white',
                    'animation_class' => 'animate-fade-in-optimized',
                ]
            ],
            [
                'type' => 'album_timeline',
                'data' => [
                    'enabled' => true,
                    'order' => 3,
                    'title' => 'Timeline khóa học',
                    'description' => 'Tài liệu và hình ảnh từ các khóa học đã diễn ra',
                    'bg_color' => 'bg-gray-25',
                    'animation_class' => 'animate-fade-in-optimized',
                ]
            ],
            [
                'type' => 'course_groups',
                'data' => [
                    'enabled' => true,
                    'order' => 4,
                    'title' => 'Nhóm học tập',
                    'description' => 'Tham gia các nhóm Facebook/Zalo để học hỏi và trao đổi kinh nghiệm',
                    'bg_color' => 'bg-white',
                    'animation_class' => 'animate-fade-in-optimized',
                ]
            ],
            [
                'type' => 'course_categories',
                'data' => [
                    'enabled' => true,
                    'order' => 5,
                    'title' => 'Khóa học theo chuyên mục',
                    'description' => 'Khám phá các khóa học được phân loại theo từng chuyên mục',
                    'bg_color' => 'bg-gray-25',
                    'animation_class' => 'animate-fade-in-optimized',
                ]
            ],
            [
                'type' => 'testimonials',
                'data' => [
                    'enabled' => true,
                    'order' => 6,
                    'title' => 'Đánh giá từ học viên',
                    'description' => 'Chia sẻ từ những học viên đã tham gia khóa học',
                    'bg_color' => 'bg-white',
                    'animation_class' => 'animate-fade-in-optimized',
                ]
            ],
            [
                'type' => 'faq',
                'data' => [
                    'enabled' => true,
                    'order' => 7,
                    'title' => 'Câu hỏi thường gặp',
                    'description' => 'Giải đáp những thắc mắc phổ biến về khóa học',
                    'bg_color' => 'bg-gray-25',
                    'animation_class' => 'animate-fade-in-optimized',
                ]
            ],
            [
                'type' => 'partners',
                'data' => [
                    'enabled' => true,
                    'order' => 8,
                    'title' => 'Đối tác tin cậy',
                    'description' => 'Những đối tác đồng hành cùng chúng tôi',
                    'bg_color' => 'bg-white',
                    'animation_class' => 'animate-fade-in-optimized',
                ]
            ],
            [
                'type' => 'blog_posts',
                'data' => [
                    'enabled' => true,
                    'order' => 9,
                    'title' => 'Bài viết mới nhất',
                    'description' => 'Cập nhật kiến thức và thông tin hữu ích',
                    'bg_color' => 'bg-gray-25',
                    'animation_class' => 'animate-fade-in-optimized',
                ]
            ],
            [
                'type' => 'footer',
                'data' => [
                    'enabled' => true,
                    'order' => 10,
                    'policy_1_title' => 'Chính sách & Điều khoản',
                    'policy_1_type' => 'custom',
                    'policy_1_url' => '#',
                    'policy_1_post' => null,
                    'policy_2_title' => 'Hệ thống đại lý',
                    'policy_2_type' => 'custom',
                    'policy_2_url' => '#',
                    'policy_2_post' => null,
                    'policy_3_title' => 'Bảo mật & Quyền riêng tư',
                    'policy_3_type' => 'custom',
                    'policy_3_url' => '#',
                    'policy_3_post' => null,
                    'copyright' => '© ' . date('Y') . ' Copyright by VBA Vũ Phúc - All Rights Reserved',
                ]
            ],

        ];
    }

    /**
     * Create a section group with consistent styling (deprecated - keeping for compatibility)
     */
    private function createSectionGroup(
        string $label,
        string $key,
        string $description,
        int $defaultOrder,
        bool $hasContent = true,
        string $defaultTitle = '',
        string $defaultDescription = ''
    ): Section {
        $schema = [
            Grid::make(3)->schema([
                Toggle::make($key . '_enabled')
                    ->label('🔘 Hiển thị')
                    ->default(true)
                    ->helperText('Bật/tắt section này'),

                TextInput::make($key . '_order')
                    ->label('📍 Thứ tự')
                    ->numeric()
                    ->default($defaultOrder)
                    ->minValue(1)
                    ->maxValue(10)
                    ->helperText('Vị trí hiển thị (1-10)')
                    ->suffixIcon('heroicon-m-arrows-up-down'),

                Placeholder::make($key . '_preview')
                    ->label('👁️ Xem trước')
                    ->content(fn() => $this->getSectionPreview($key))
            ])
        ];

        if ($hasContent) {
            $schema[] = TextInput::make($key . '_title')
                ->label('📝 Tiêu đề')
                ->default($defaultTitle)
                ->maxLength(255)
                ->prefixIcon('heroicon-m-pencil');

            $schema[] = Textarea::make($key . '_description')
                ->label('Mô tả')
                ->default($defaultDescription)
                ->rows(2);
        }

        return Section::make($label)
            ->description($description)
            ->schema($schema)
            ->collapsible()
            ->collapsed(true);
    }

    /**
     * Get section preview content
     */
    private function getSectionPreview(string $key): string
    {
        $previews = [
            'hero_banner' => 'Banner với slider',
            'courses_overview' => 'Grid khóa học',
            'album_timeline' => 'Timeline album',
            'course_groups' => 'Cards nhóm học',
            'course_categories' => 'Danh mục khóa học',
            'testimonials' => 'Slider đánh giá',
            'faq' => 'Accordion FAQ',
            'partners' => 'Logo đối tác',
            'blog_posts' => 'Grid bài viết',
            'footer' => 'Footer với 3 chính sách',
        ];

        return $previews[$key] ?? 'Nội dung phần';
    }



    public function save(): void
    {
        $data = $this->form->getState();
        $sections = $data['sections'] ?? [];

        // Convert Builder format back to WebDesign model format
        $webDesignData = [];

        // Lấy thứ tự từ vị trí trong Builder array (index + 1)
        foreach ($sections as $index => $section) {
            $type = $section['type'];
            $sectionData = $section['data'];

            // Map section data to WebDesign fields
            $webDesignData[$type . '_enabled'] = $sectionData['enabled'] ?? true;
            // ✅ Sử dụng index + 1 làm order thay vì field order
            $webDesignData[$type . '_order'] = $index + 1;

            // Add content fields if they exist
            if (isset($sectionData['title'])) {
                $webDesignData[$type . '_title'] = $sectionData['title'];
            }
            if (isset($sectionData['description'])) {
                $webDesignData[$type . '_description'] = $sectionData['description'];
            }

            // Handle footer specific fields
            if ($type === 'footer') {
                // Policy 1
                if (isset($sectionData['policy_1_title'])) {
                    $webDesignData['footer_policy_1_title'] = $sectionData['policy_1_title'];
                }
                if (isset($sectionData['policy_1_type'])) {
                    $webDesignData['footer_policy_1_type'] = $sectionData['policy_1_type'];
                }
                if (isset($sectionData['policy_1_url'])) {
                    $webDesignData['footer_policy_1_url'] = $sectionData['policy_1_url'];
                }
                if (isset($sectionData['policy_1_post'])) {
                    $webDesignData['footer_policy_1_post'] = $sectionData['policy_1_post'];
                }

                // Policy 2
                if (isset($sectionData['policy_2_title'])) {
                    $webDesignData['footer_policy_2_title'] = $sectionData['policy_2_title'];
                }
                if (isset($sectionData['policy_2_type'])) {
                    $webDesignData['footer_policy_2_type'] = $sectionData['policy_2_type'];
                }
                if (isset($sectionData['policy_2_url'])) {
                    $webDesignData['footer_policy_2_url'] = $sectionData['policy_2_url'];
                }
                if (isset($sectionData['policy_2_post'])) {
                    $webDesignData['footer_policy_2_post'] = $sectionData['policy_2_post'];
                }

                // Policy 3
                if (isset($sectionData['policy_3_title'])) {
                    $webDesignData['footer_policy_3_title'] = $sectionData['policy_3_title'];
                }
                if (isset($sectionData['policy_3_type'])) {
                    $webDesignData['footer_policy_3_type'] = $sectionData['policy_3_type'];
                }
                if (isset($sectionData['policy_3_url'])) {
                    $webDesignData['footer_policy_3_url'] = $sectionData['policy_3_url'];
                }
                if (isset($sectionData['policy_3_post'])) {
                    $webDesignData['footer_policy_3_post'] = $sectionData['policy_3_post'];
                }

                // Copyright
                if (isset($sectionData['copyright'])) {
                    $webDesignData['footer_copyright'] = $sectionData['copyright'];
                }

                // Company Info
                if (isset($sectionData['company_brand_name'])) {
                    $webDesignData['footer_company_brand_name'] = $sectionData['company_brand_name'];
                }
                if (isset($sectionData['company_business_license'])) {
                    $webDesignData['footer_company_business_license'] = $sectionData['company_business_license'];
                }
                if (isset($sectionData['company_director_info'])) {
                    $webDesignData['footer_company_director_info'] = $sectionData['company_director_info'];
                }
            }

        }

        $webDesign = WebDesign::first();

        if ($webDesign) {
            $webDesign->update($webDesignData);
        } else {
            WebDesign::create($webDesignData);
        }

        // Clear cache với ViewServiceProvider
        \App\Providers\ViewServiceProvider::refreshCache('webdesign');
        Cache::forget('web_design_settings');

        // Count enabled sections
        $enabledCount = count(array_filter($sections, fn($section) => $section['data']['enabled'] ?? true));

        Notification::make()
            ->title('Giao diện đã được cập nhật thành công!')
            ->body("{$enabledCount} phần đang hiển thị trên trang chủ. Thứ tự đã được sắp xếp theo vị trí kéo thả.")
            ->success()
            ->duration(5000)
            ->send();
    }
}
