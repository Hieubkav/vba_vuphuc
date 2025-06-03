<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\PostResource\Pages;
use App\Filament\Admin\Resources\PostResource\RelationManagers;
use App\Models\Post;
use App\Services\ImageService;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
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

class PostResource extends Resource
{
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
                Section::make('Thông tin bài viết')
                    ->schema([
                        TextInput::make('title')
                            ->label('Tiêu đề')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (string $state, callable $set) => $set('slug', Str::slug($state))),

                        TextInput::make('slug')
                            ->label('Đường dẫn')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255)
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
                            ),

                        Select::make('type')
                            ->label('Loại bài viết')
                            ->options([
                                'normal' => 'Bài viết thường',
                                'news' => 'Tin tức',
                                'service' => 'Dịch vụ',
                                'course' => 'Khóa học',
                            ])
                            ->default('normal')
                            ->required(),

                        Select::make('category_id')
                            ->label('Danh mục')
                            ->relationship('category', 'name')
                            ->searchable()
                            ->preload()
                            ->nullable()
                            ->createOptionForm([
                                TextInput::make('name')
                                    ->label('Tên danh mục')
                                    ->required()
                                    ->maxLength(255)
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(fn (string $state, callable $set) => $set('slug', Str::slug($state))),
                                TextInput::make('slug')
                                    ->label('Đường dẫn')
                                    ->required()
                                    ->maxLength(255),
                            ]),

                        FileUpload::make('thumbnail') // Giữ tên trường là 'thumbnail' để khớp với database
                            ->label('Hình đại diện')
                            ->helperText('💡 Kích thước khuyến nghị: 1200x630px (tỷ lệ 1.91:1) cho hiển thị tối ưu trên mạng xã hội')
                            ->image()
                            ->directory('posts/thumbnails')
                            ->visibility('public')
                            ->maxSize(5120) // Tăng lên 5MB để cho phép ảnh chất lượng cao
                            ->imageEditor()
                            ->imagePreviewHeight('200') // Hiển thị preview lớn hơn
                            ->nullable()
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                            // Sử dụng saveImageWithAspectRatio để không bị méo ảnh
                            ->saveUploadedFileUsing(function ($file, $get) {
                                $imageService = app(ImageService::class);
                                $title = $get('title') ?? 'bai-viet';
                                return $imageService->saveImageWithAspectRatio(
                                    $file,
                                    'posts/thumbnails',
                                    1200,  // max width
                                    630,   // max height - giữ tỷ lệ gốc
                                    90,    // quality cao hơn
                                    "thumbnail-{$title}" // SEO-friendly name
                                );
                            }),
                    ])->columns(2),

                Section::make('Nội dung bài viết')
                    ->schema([
                        RichEditor::make('content')
                            ->label('Nội dung chi tiết')
                            ->fileAttachmentsDisk('public')
                            ->fileAttachmentsDirectory('posts')
                            ->required()
                            ->columnSpanFull(),
                    ]),

                Section::make('SEO và Thông tin khác')
                    ->description('Sử dụng nút bên dưới để tự động tạo SEO title và description. OG image sẽ tự động copy từ hình đại diện khi lưu.')
                    ->schema([
                        Actions::make([
                            Action::make('generateAllSeo')
                                ->label('🚀 Tự động tạo SEO')
                                ->icon('heroicon-m-sparkles')
                                ->color('success')
                                ->size('lg')
                                ->action(function (Set $set, Get $get) {
                                    $title = $get('title');
                                    $content = $get('content');

                                    $messages = [];

                                    // Tạo SEO title
                                    if (!empty($title)) {
                                        $seoTitle = static::generateSeoTitle($title);
                                        $set('seo_title', $seoTitle);
                                        $messages[] = 'SEO title';
                                    }

                                    // Tạo SEO description
                                    if (!empty($content)) {
                                        $seoDescription = static::generateSeoDescription($content);
                                        $set('seo_description', $seoDescription);
                                        $messages[] = 'SEO description';
                                    }

                                    // Thông báo kết quả
                                    if (empty($messages)) {
                                        \Filament\Notifications\Notification::make()
                                            ->title('Chưa thể tạo SEO')
                                            ->body('Vui lòng nhập tiêu đề và nội dung trước khi tạo SEO.')
                                            ->warning()
                                            ->send();
                                    } else {
                                        \Filament\Notifications\Notification::make()
                                            ->title('Đã tạo SEO thành công!')
                                            ->body('Đã tạo: ' . implode(', ', $messages) . '. OG image sẽ tự động copy từ hình đại diện khi lưu.')
                                            ->success()
                                            ->send();
                                    }
                                })
                        ])->columnSpanFull(),

                        TextInput::make('seo_title')
                            ->label('Tiêu đề SEO')
                            ->helperText('Tối đa 60 ký tự cho SEO tốt nhất')
                            ->maxLength(255),

                        Textarea::make('seo_description')
                            ->label('Mô tả SEO')
                            ->helperText('Tối đa 155 ký tự cho SEO tốt nhất')
                            ->rows(3)
                            ->maxLength(255),

                        FileUpload::make('og_image_link')
                            ->label('Hình ảnh OG (Social Media)')
                            ->helperText('Kích thước tối ưu: 1200x630px')
                            ->image()
                            ->directory('posts/og-images')
                            ->visibility('public')
                            ->imageResizeMode('cover')
                            ->imageResizeTargetWidth(1200)
                            ->imageResizeTargetHeight(630)
                            ->saveUploadedFileUsing(function ($file, $get) {
                                $imageService = app(\App\Services\ImageService::class);
                                $title = $get('title') ?? 'bai-viet';
                                return $imageService->saveImage(
                                    $file,
                                    'posts/og-images',
                                    1200,  // width
                                    630,   // height
                                    85,    // quality
                                    "og-image-{$title}" // SEO-friendly name
                                );
                            }),
                    ])->columns(2),

                Section::make('Cấu hình hiển thị')
                    ->schema([
                        Toggle::make('is_featured')
                            ->label('Nổi bật')
                            ->default(false)
                            ->onColor('success')
                            ->offColor('danger'),

                        Select::make('status')
                            ->label('Trạng thái')
                            ->options([
                                'active' => 'Hiển thị',
                                'inactive' => 'Ẩn',
                            ])
                            ->default('active')
                            ->required(),

                        TextInput::make('order')
                            ->label('Thứ tự hiển thị')
                            ->integer()
                            ->default(0),
                    ])->columns(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('thumbnail') // Giữ tên trường là 'thumbnail' để khớp với database
                    ->label('Hình đại diện')
                    ->defaultImageUrl(fn() => asset('images/default-post.jpg'))
                    ->size(80) // Kích thước cố định
                    ->extraImgAttributes(['class' => 'object-cover rounded-lg']) // Không bị méo, bo góc
                    ->tooltip(fn ($record) => $record->title), // Hiển thị tiêu đề khi hover

                TextColumn::make('title')
                    ->label('Tiêu đề')
                    ->searchable()
                    ->sortable()
                    ->limit(50),

                TextColumn::make('type')
                    ->label('Loại')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'service' => 'danger',
                        'news' => 'info',
                        'course' => 'warning',
                        'normal' => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'service' => 'Dịch vụ',
                        'news' => 'Tin tức',
                        'course' => 'Khóa học',
                        'normal' => 'Bài viết',
                    })
                    ->sortable(),

                TextColumn::make('category.name')
                    ->label('Danh mục')
                    ->searchable()
                    ->sortable(),

                ToggleColumn::make('is_featured')
                    ->label('Nổi bật')
                    ->sortable(),

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
                    ->sortable(),

                TextColumn::make('order')
                    ->label('Thứ tự')
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Ngày tạo')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->reorderable('order')
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->label('Loại bài viết')
                    ->options([
                        'normal' => 'Bài viết thường',
                        'news' => 'Tin tức',
                        'service' => 'Dịch vụ',
                        'course' => 'Khóa học',
                    ]),

                Tables\Filters\SelectFilter::make('category_id')
                    ->relationship('category', 'name')
                    ->label('Danh mục'),

                Tables\Filters\TernaryFilter::make('is_featured')
                    ->label('Nổi bật'),

                Tables\Filters\SelectFilter::make('status')
                    ->label('Trạng thái')
                    ->options([
                        'active' => 'Hiển thị',
                        'inactive' => 'Ẩn',
                    ]),
            ])
            ->actions([
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
        return static::getModel()::count();
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'success';
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