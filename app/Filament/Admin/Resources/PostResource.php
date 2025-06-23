<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\PostResource\Pages;
use App\Filament\Admin\Resources\PostResource\RelationManagers;
use App\Filament\Admin\Resources\PostCategoryResource;
use App\Models\Post;
use App\Traits\HasImageUpload;

use App\Actions\OptimizePostMedia;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Builder;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class PostResource extends Resource
{
    use HasImageUpload;

    protected static ?string $model = Post::class;

    protected static ?string $modelLabel = 'bài viết';

    protected static ?string $pluralModelLabel = 'bài viết';

    protected static ?string $navigationIcon = 'heroicon-o-newspaper';

    protected static ?string $navigationGroup = 'Quản lý nội dung';

    protected static ?string $navigationLabel = 'Bài viết';

    protected static ?int $navigationSort = 20;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Tabs::make('Thông tin bài viết')
                    ->tabs([
                        Tabs\Tab::make('Nội dung & Thông tin')
                            ->schema([
                                Section::make('Thông tin cơ bản')
                                    ->schema([
                                        TextInput::make('title')
                                            ->label('Tiêu đề')
                                            ->required()
                                            ->maxLength(255)
                                            ->live(onBlur: true)
                                            ->afterStateUpdated(fn (string $state, callable $set) => $set('slug', Str::slug($state)))
                                            ->columnSpan(2),

                                        TextInput::make('slug')
                                            ->label('Đường dẫn')
                                            ->unique(ignoreRecord: true)
                                            ->maxLength(255)
                                            ->helperText('Để trống để tự động tạo từ tiêu đề')
                                            ->suffixAction(
                                                Action::make('generateSlug')
                                                    ->icon('heroicon-m-link')
                                                    ->tooltip('Tự động tạo từ tiêu đề')
                                                    ->action(function (Set $set, Get $get) {
                                                        $title = $get('title');
                                                        if (!empty($title)) {
                                                            $set('slug', Str::slug($title));
                                                        }
                                                    })
                                            )
                                            ->columnSpan(1),

                                        CheckboxList::make('categories')
                                            ->label('Chuyên mục')
                                            ->relationship('categories', 'name')
                                            ->searchable()
                                            ->bulkToggleable()
                                            ->columns(2)
                                            ->gridDirection('row')
                                            ->helperText('Chọn một hoặc nhiều chuyên mục cho bài viết')
                                            ->columnSpan(2),

                                        FileUpload::make('thumbnail')
                                            ->label('Hình đại diện')
                                            ->image()
                                            ->directory('posts/thumbnails')
                                            ->visibility('public')
                                            ->maxSize(5120)
                                            ->imageEditor()
                                            ->imageEditorAspectRatios([
                                                '16:9',
                                                '4:3',
                                                '1:1',
                                            ])
                                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                                            ->saveUploadedFileUsing(function ($file, $get) {
                                                try {
                                                    $title = $get('title') ?? 'post';
                                                    $customName = 'post-' . $title;

                                                    $result = \App\Actions\ConvertImageToWebp::run(
                                                        $file,
                                                        'posts/thumbnails',
                                                        $customName,
                                                        1200,
                                                        630
                                                    );

                                                    if (!$result) {
                                                        throw new \Exception('Không thể xử lý file ảnh');
                                                    }

                                                    return $result;
                                                } catch (\Exception $e) {
                                                    Log::error('PostResource thumbnail upload error: ' . $e->getMessage());
                                                    throw new \Exception('Lỗi khi tải ảnh lên: ' . $e->getMessage());
                                                }
                                            })
                                            ->helperText('Ảnh sẽ được tự động chuyển sang WebP. Kích thước tối ưu: 1200x630px')
                                            ->imagePreviewHeight('200')
                                            ->columnSpan(1),

                                        Textarea::make('excerpt')
                                            ->label('Tóm tắt bài viết')
                                            ->rows(3)
                                            ->maxLength(500)
                                            ->helperText('Tóm tắt ngắn gọn về nội dung bài viết. Để trống để tự động tạo.')
                                            ->columnSpan(1),

                                        Toggle::make('is_featured')
                                            ->label('Bài viết nổi bật')
                                            ->default(false)
                                            ->helperText('Hiển thị trong danh sách bài viết nổi bật')
                                            ->columnSpan(1),

                                        TextInput::make('order')
                                            ->label('Thứ tự')
                                            ->numeric()
                                            ->default(0)
                                            ->helperText('Số thứ tự hiển thị (càng nhỏ càng ưu tiên)')
                                            ->columnSpan(1),

                                        Select::make('status')
                                            ->label('Trạng thái')
                                            ->options([
                                                'active' => 'Hiển thị',
                                                'inactive' => 'Ẩn',
                                            ])
                                            ->default('active')
                                            ->required()
                                            ->columnSpan(1),
                                    ])->columns(2),

                                Section::make('Nội dung bài viết')
                                    ->description('🎨 Tạo nội dung đa dạng với các khối text, ảnh, video, audio. Kéo thả để sắp xếp lại thứ tự.')
                                    ->schema([
                                        Builder::make('content_builder')
                                            ->label('Nội dung bài viết')
                                            ->blocks([
                                                Builder\Block::make('paragraph')
                                                    ->label('📝 Đoạn văn')
                                                    ->icon('heroicon-m-document-text')
                                                    ->schema([
                                                        RichEditor::make('content')
                                                            ->label('Nội dung')
                                                            ->placeholder('Nhập nội dung đoạn văn...')
                                                            ->toolbarButtons([
                                                                'bold', 'italic', 'underline', 'strike',
                                                                'link', 'bulletList', 'orderedList',
                                                                'blockquote', 'codeBlock', 'h2', 'h3'
                                                            ])
                                                            ->required(),
                                                    ]),

                                                Builder\Block::make('heading')
                                                    ->label('📋 Tiêu đề')
                                                    ->icon('heroicon-m-bars-3')
                                                    ->schema([
                                                        Select::make('level')
                                                            ->label('Cấp độ')
                                                            ->options([
                                                                'h2' => 'H2 - Tiêu đề lớn',
                                                                'h3' => 'H3 - Tiêu đề trung',
                                                                'h4' => 'H4 - Tiêu đề nhỏ',
                                                            ])
                                                            ->default('h2')
                                                            ->required(),
                                                        TextInput::make('text')
                                                            ->label('Nội dung tiêu đề')
                                                            ->required(),
                                                    ]),

                                                Builder\Block::make('image')
                                                    ->label('🖼️ Hình ảnh')
                                                    ->icon('heroicon-m-photo')
                                                    ->schema([
                                                        FileUpload::make('image')
                                                            ->label('Chọn ảnh')
                                                            ->image()
                                                            ->directory('posts/content')
                                                            ->visibility('public')
                                                            ->maxSize(5120)
                                                            ->imageEditor()
                                                            ->required(),
                                                        TextInput::make('alt')
                                                            ->label('Alt text')
                                                            ->placeholder('Mô tả ảnh cho SEO...'),
                                                        TextInput::make('caption')
                                                            ->label('Chú thích')
                                                            ->placeholder('Chú thích hiển thị dưới ảnh...'),
                                                        Select::make('alignment')
                                                            ->label('Căn chỉnh')
                                                            ->options([
                                                                'left' => 'Trái',
                                                                'center' => 'Giữa',
                                                                'right' => 'Phải',
                                                            ])
                                                            ->default('center'),
                                                    ]),

                                                Builder\Block::make('gallery')
                                                    ->label('🖼️ Thư viện ảnh')
                                                    ->icon('heroicon-m-photo')
                                                    ->schema([
                                                        FileUpload::make('images')
                                                            ->label('Chọn nhiều ảnh')
                                                            ->image()
                                                            ->multiple()
                                                            ->directory('posts/galleries')
                                                            ->visibility('public')
                                                            ->maxSize(5120)
                                                            ->maxFiles(10)
                                                            ->imageEditor()
                                                            ->required(),
                                                        TextInput::make('caption')
                                                            ->label('Chú thích chung')
                                                            ->placeholder('Chú thích cho toàn bộ thư viện...'),
                                                        Select::make('columns')
                                                            ->label('Số cột hiển thị')
                                                            ->options([
                                                                '2' => '2 cột',
                                                                '3' => '3 cột',
                                                                '4' => '4 cột',
                                                            ])
                                                            ->default('3'),
                                                    ]),

                                                Builder\Block::make('video')
                                                    ->label('🎥 Video')
                                                    ->icon('heroicon-m-play')
                                                    ->schema([
                                                        Select::make('type')
                                                            ->label('Loại video')
                                                            ->options([
                                                                'youtube' => 'YouTube',
                                                                'vimeo' => 'Vimeo',
                                                                'upload' => 'Upload file',
                                                            ])
                                                            ->default('youtube')
                                                            ->live()
                                                            ->required(),

                                                        TextInput::make('url')
                                                            ->label('URL Video')
                                                            ->placeholder('https://www.youtube.com/watch?v=...')
                                                            ->url()
                                                            ->visible(fn (Get $get) => in_array($get('type'), ['youtube', 'vimeo']))
                                                            ->required(fn (Get $get) => in_array($get('type'), ['youtube', 'vimeo'])),

                                                        FileUpload::make('file')
                                                            ->label('Upload video')
                                                            ->acceptedFileTypes(['video/mp4', 'video/webm', 'video/ogg'])
                                                            ->directory('posts/videos')
                                                            ->visibility('public')
                                                            ->maxSize(102400) // 100MB
                                                            ->visible(fn (Get $get) => $get('type') === 'upload')
                                                            ->required(fn (Get $get) => $get('type') === 'upload'),

                                                        TextInput::make('title')
                                                            ->label('Tiêu đề video')
                                                            ->placeholder('Tiêu đề hiển thị...'),

                                                        TextInput::make('caption')
                                                            ->label('Chú thích video')
                                                            ->placeholder('Mô tả về video...'),

                                                        Toggle::make('autoplay')
                                                            ->label('Tự động phát')
                                                            ->default(false),
                                                    ]),

                                                Builder\Block::make('audio')
                                                    ->label('🎵 Audio')
                                                    ->icon('heroicon-m-musical-note')
                                                    ->schema([
                                                        FileUpload::make('file')
                                                            ->label('Upload audio')
                                                            ->acceptedFileTypes(['audio/mpeg', 'audio/wav', 'audio/ogg'])
                                                            ->directory('posts/audio')
                                                            ->visibility('public')
                                                            ->maxSize(51200) // 50MB
                                                            ->required(),

                                                        TextInput::make('title')
                                                            ->label('Tiêu đề audio')
                                                            ->placeholder('Tiêu đề hiển thị...'),

                                                        TextInput::make('artist')
                                                            ->label('Nghệ sĩ/Tác giả')
                                                            ->placeholder('Tên nghệ sĩ hoặc tác giả...'),

                                                        TextInput::make('caption')
                                                            ->label('Mô tả')
                                                            ->placeholder('Mô tả về audio...'),

                                                        FileUpload::make('thumbnail')
                                                            ->label('Ảnh thumbnail')
                                                            ->image()
                                                            ->directory('posts/audio-thumbnails')
                                                            ->visibility('public')
                                                            ->maxSize(2048),
                                                    ]),

                                                Builder\Block::make('quote')
                                                    ->label('💬 Trích dẫn')
                                                    ->icon('heroicon-m-chat-bubble-left-right')
                                                    ->schema([
                                                        Textarea::make('content')
                                                            ->label('Nội dung trích dẫn')
                                                            ->placeholder('Nhập nội dung trích dẫn...')
                                                            ->rows(3)
                                                            ->required(),
                                                        TextInput::make('author')
                                                            ->label('Tác giả')
                                                            ->placeholder('Tên tác giả...'),
                                                        TextInput::make('source')
                                                            ->label('Nguồn')
                                                            ->placeholder('Tên sách, bài viết, website...'),
                                                        Select::make('style')
                                                            ->label('Kiểu hiển thị')
                                                            ->options([
                                                                'default' => 'Mặc định',
                                                                'highlight' => 'Nổi bật',
                                                                'minimal' => 'Tối giản',
                                                            ])
                                                            ->default('default'),
                                                    ]),

                                                Builder\Block::make('code')
                                                    ->label('💻 Code')
                                                    ->icon('heroicon-m-code-bracket')
                                                    ->schema([
                                                        Textarea::make('content')
                                                            ->label('Mã nguồn')
                                                            ->placeholder('Nhập code...')
                                                            ->rows(8)
                                                            ->required(),
                                                        Select::make('language')
                                                            ->label('Ngôn ngữ lập trình')
                                                            ->options([
                                                                'html' => 'HTML',
                                                                'css' => 'CSS',
                                                                'javascript' => 'JavaScript',
                                                                'php' => 'PHP',
                                                                'python' => 'Python',
                                                                'java' => 'Java',
                                                                'sql' => 'SQL',
                                                                'json' => 'JSON',
                                                                'xml' => 'XML',
                                                                'bash' => 'Bash',
                                                                'other' => 'Khác',
                                                            ])
                                                            ->default('html'),
                                                        TextInput::make('title')
                                                            ->label('Tiêu đề code')
                                                            ->placeholder('Tên file hoặc mô tả...'),
                                                        Toggle::make('line_numbers')
                                                            ->label('Hiển thị số dòng')
                                                            ->default(true),
                                                    ]),

                                                Builder\Block::make('list')
                                                    ->label('📋 Danh sách')
                                                    ->icon('heroicon-m-list-bullet')
                                                    ->schema([
                                                        Select::make('type')
                                                            ->label('Loại danh sách')
                                                            ->options([
                                                                'bullet' => '• Bullet points',
                                                                'numbered' => '1. Đánh số',
                                                                'checklist' => '✓ Checklist',
                                                            ])
                                                            ->default('bullet')
                                                            ->required(),
                                                        Textarea::make('items')
                                                            ->label('Các mục (mỗi dòng một mục)')
                                                            ->placeholder("Mục 1\nMục 2\nMục 3")
                                                            ->rows(6)
                                                            ->required(),
                                                        TextInput::make('title')
                                                            ->label('Tiêu đề danh sách')
                                                            ->placeholder('Tiêu đề cho danh sách...'),
                                                    ]),

                                                Builder\Block::make('divider')
                                                    ->label('➖ Đường phân cách')
                                                    ->icon('heroicon-m-minus')
                                                    ->schema([
                                                        Select::make('style')
                                                            ->label('Kiểu đường kẻ')
                                                            ->options([
                                                                'solid' => 'Liền',
                                                                'dashed' => 'Gạch ngang',
                                                                'dotted' => 'Chấm',
                                                                'double' => 'Đôi',
                                                                'gradient' => 'Gradient',
                                                            ])
                                                            ->default('solid'),
                                                        Select::make('thickness')
                                                            ->label('Độ dày')
                                                            ->options([
                                                                'thin' => 'Mỏng',
                                                                'medium' => 'Trung bình',
                                                                'thick' => 'Dày',
                                                            ])
                                                            ->default('medium'),
                                                        Select::make('spacing')
                                                            ->label('Khoảng cách')
                                                            ->options([
                                                                'small' => 'Nhỏ',
                                                                'medium' => 'Trung bình',
                                                                'large' => 'Lớn',
                                                            ])
                                                            ->default('medium'),
                                                    ]),

                                                Builder\Block::make('cta')
                                                    ->label('🎯 Call to Action')
                                                    ->icon('heroicon-m-cursor-arrow-rays')
                                                    ->schema([
                                                        TextInput::make('title')
                                                            ->label('Tiêu đề')
                                                            ->placeholder('Tiêu đề hành động...')
                                                            ->required(),
                                                        Textarea::make('description')
                                                            ->label('Mô tả')
                                                            ->placeholder('Mô tả chi tiết...')
                                                            ->rows(2),
                                                        TextInput::make('button_text')
                                                            ->label('Text nút')
                                                            ->placeholder('Nhấn vào đây')
                                                            ->required(),
                                                        TextInput::make('button_url')
                                                            ->label('Link nút')
                                                            ->placeholder('https://...')
                                                            ->url()
                                                            ->required(),
                                                        Select::make('style')
                                                            ->label('Kiểu hiển thị')
                                                            ->options([
                                                                'primary' => 'Chính (Xanh)',
                                                                'secondary' => 'Phụ (Xám)',
                                                                'success' => 'Thành công (Xanh lá)',
                                                                'warning' => 'Cảnh báo (Vàng)',
                                                                'danger' => 'Nguy hiểm (Đỏ)',
                                                            ])
                                                            ->default('primary'),
                                                        Select::make('size')
                                                            ->label('Kích thước')
                                                            ->options([
                                                                'small' => 'Nhỏ',
                                                                'medium' => 'Trung bình',
                                                                'large' => 'Lớn',
                                                            ])
                                                            ->default('medium'),
                                                    ]),
                                            ])
                                            ->collapsible()
                                            ->reorderable()
                                            ->addActionLabel('Thêm khối nội dung')
                                            ->blockNumbers(false)
                                            ->columnSpanFull(),
                                    ]),
                            ]),

                        Tabs\Tab::make('SEO & Tối ưu hóa')
                            ->schema([
                                Section::make('SEO Meta Tags')
                                    ->description('Các trường SEO sẽ tự động tạo nếu để trống khi lưu')
                                    ->schema([
                                        TextInput::make('seo_title')
                                            ->label('Tiêu đề SEO')
                                            ->maxLength(60)
                                            ->helperText('Tối đa 60 ký tự. Để trống để tự động tạo từ tiêu đề bài viết'),

                                        Textarea::make('seo_description')
                                            ->label('Mô tả SEO')
                                            ->rows(3)
                                            ->maxLength(160)
                                            ->helperText('Tối đa 160 ký tự. Để trống để tự động tạo từ nội dung bài viết'),

                                        // Trường og_image_link đã được định nghĩa trong tab "Hình ảnh & Media"
                                        // Không cần duplicate ở đây

                                        TextInput::make('reading_time')
                                            ->label('Thời gian đọc (phút)')
                                            ->numeric()
                                            ->helperText('Để trống để tự động tính toán'),
                                    ])->columns(1)
                                    ->columnSpanFull(),
                            ]),
                    ])
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('order')
                    ->label('Thứ tự')
                    ->sortable()
                    ->alignCenter()
                    ->width('80px'),

                ImageColumn::make('thumbnail')
                    ->label('Ảnh')
                    ->circular()
                    ->size(50),

                TextColumn::make('title')
                    ->label('Tiêu đề')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->description(function ($record): string {
                        $parts = [];
                        if ($record->categories->count() > 0) {
                            $categoryNames = $record->categories->pluck('name')->join(', ');
                            $parts[] = "Chuyên mục: {$categoryNames}";
                        }
                        if ($record->reading_time) {
                            $parts[] = "Đọc: {$record->reading_time} phút";
                        }
                        if ($record->hasRichContent()) {
                            $parts[] = "📝 Nội dung đa dạng";
                        }
                        return implode(' • ', $parts) ?: 'Chưa phân loại';
                    }),

                TextColumn::make('categories.name')
                    ->label('Chuyên mục')
                    ->badge()
                    ->separator(', ')
                    ->color('info')
                    ->limit(2),

                Tables\Columns\IconColumn::make('is_featured')
                    ->label('Nổi bật')
                    ->boolean()
                    ->trueIcon('heroicon-o-star')
                    ->falseIcon('heroicon-o-star')
                    ->trueColor('warning')
                    ->falseColor('gray')
                    ->width('80px'),

                TextColumn::make('status')
                    ->label('Trạng thái')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'active' => 'success',
                        'inactive' => 'danger',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'active' => 'Hiển thị',
                        'inactive' => 'Ẩn',
                    })
                    ->width('100px'),

                TextColumn::make('published_at')
                    ->label('Xuất bản')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('created_at')
                    ->label('Ngày tạo')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->reorderable('order')
            ->filters([
                Tables\Filters\SelectFilter::make('categories')
                    ->relationship('categories', 'name')
                    ->label('Chuyên mục'),

                Tables\Filters\SelectFilter::make('status')
                    ->label('Trạng thái')
                    ->options([
                        'active' => 'Hiển thị',
                        'inactive' => 'Ẩn',
                    ]),

                Tables\Filters\TernaryFilter::make('is_featured')
                    ->label('Bài viết nổi bật')
                    ->boolean()
                    ->trueLabel('Nổi bật')
                    ->falseLabel('Thường')
                    ->native(false),

                Tables\Filters\Filter::make('has_rich_content')
                    ->label('Có nội dung đa dạng')
                    ->query(fn ($query) => $query->whereNotNull('content_builder'))
                    ->toggle(),

                Tables\Filters\Filter::make('published')
                    ->label('Đã xuất bản')
                    ->query(fn ($query) => $query->published())
                    ->toggle(),
            ])
            ->actions([
                Tables\Actions\Action::make('view_frontend')
                    ->label('Xem trên website')
                    ->icon('heroicon-o-eye')
                    ->color('info')
                    ->url(fn ($record) => route('posts.show', $record->slug))
                    ->openUrlInNewTab(),
                Tables\Actions\Action::make('view_categories')
                    ->label('Xem chuyên mục')
                    ->icon('heroicon-o-folder')
                    ->color('warning')
                    ->url(fn ($record) => $record->categories->count() > 0 ?
                        PostCategoryResource::getUrl('edit', ['record' => $record->categories->first()->id]) : null)
                    ->visible(fn ($record) => $record->categories->count() > 0),

                Tables\Actions\Action::make('optimize_media')
                    ->label('Tối ưu Media')
                    ->icon('heroicon-o-photo')
                    ->color('info')
                    ->action(function (Post $record) {
                        $result = OptimizePostMedia::run($record);

                        $message = "✅ Hoàn thành!\n";
                        $message .= "📸 Ảnh tối ưu: {$result['images_optimized']}\n";
                        $message .= "🎬 Media xử lý: {$result['media_processed']}\n";

                        if ($result['total_size_saved'] > 0) {
                            $sizeFormatted = number_format($result['total_size_saved'] / 1024, 2) . ' KB';
                            $message .= "💾 Tiết kiệm: {$sizeFormatted}";
                        }

                        Notification::make()
                            ->title('Media đã được tối ưu')
                            ->body($message)
                            ->success()
                            ->send();
                    })
                    ->requiresConfirmation()
                    ->modalHeading('Tối ưu Media')
                    ->modalDescription('Tối ưu hóa tất cả hình ảnh và media của bài viết này. Quá trình có thể mất vài phút.')
                    ->modalSubmitActionLabel('Bắt đầu tối ưu'),

                Tables\Actions\EditAction::make()
                    ->label('Sửa'),
                Tables\Actions\DeleteAction::make()
                    ->label('Xóa'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->label('Xóa đã chọn'),
                ]),
            ])
            ->defaultSort('order', 'asc');
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\PostImagesRelationManager::class,
            RelationManagers\PostMediaRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPosts::route('/'),
            'create' => Pages\CreatePost::route('/create'),
            'edit' => Pages\EditPost::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return (string) static::getModel()::where('status', 'active')->count();
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'success';
    }

    /**
     * Lấy danh sách cột cần thiết cho table
     */
    protected static function getTableColumns(): array
    {
        return [
            'id',
            'title',
            'slug',
            'type',
            'status',
            'is_featured',
            'order',
            'thumbnail',

            'created_at'
        ];
    }

    /**
     * Lấy relationships cần thiết cho form
     */
    protected static function getFormRelationships(): array
    {
        return [
            'categories' => function($query) {
                $query->select(['id', 'name', 'slug']);
            },
            'images' => function($query) {
                $query->where('status', 'active')
                      ->orderBy('order')
                      ->limit(10);
            }
        ];
    }

    /**
     * Lấy các cột có thể search
     */
    protected static function getSearchableColumns(): array
    {
        return ['title', 'content'];
    }

    /**
     * Tạo SEO title từ title gốc
     */
    public static function generateSeoTitle(string $title): string
    {
        // Giới hạn độ dài SEO title trong khoảng 50-60 ký tự
        $maxLength = 60;

        if (strlen($title) <= $maxLength) {
            return $title;
        }

        // Cắt ngắn tại từ cuối cùng để tránh cắt giữa từ
        $truncated = substr($title, 0, $maxLength - 3);
        $lastSpace = strrpos($truncated, ' ');

        if ($lastSpace !== false) {
            $truncated = substr($truncated, 0, $lastSpace);
        }

        return $truncated . '...';
    }

    /**
     * Tạo SEO description từ content
     */
    public static function generateSeoDescription(string $content): string
    {
        // Loại bỏ HTML tags
        $plainText = strip_tags($content);

        // Loại bỏ khoảng trắng thừa
        $plainText = preg_replace('/\s+/', ' ', trim($plainText));

        // Giới hạn độ dài SEO description trong khoảng 150-160 ký tự
        $maxLength = 155;

        if (strlen($plainText) <= $maxLength) {
            return $plainText;
        }

        // Cắt ngắn tại từ cuối cùng để tránh cắt giữa từ
        $truncated = substr($plainText, 0, $maxLength - 3);
        $lastSpace = strrpos($truncated, ' ');

        if ($lastSpace !== false) {
            $truncated = substr($truncated, 0, $lastSpace);
        }

        return $truncated . '...';
    }
}