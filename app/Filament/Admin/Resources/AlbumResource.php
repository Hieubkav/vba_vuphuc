<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\AlbumResource\Pages;


use App\Models\Album;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class AlbumResource extends Resource
{
    // Bỏ hết traits để đơn giản hóa

    protected static ?string $model = Album::class;

    protected static ?string $navigationIcon = 'heroicon-o-book-open';

    protected static ?string $navigationLabel = 'Album - Timeline';

    protected static ?string $modelLabel = 'album timeline';

    protected static ?string $pluralModelLabel = 'album timeline';

    protected static ?string $navigationGroup = 'Quản lý khóa học';

    protected static ?int $navigationSort = 7;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Tabs::make('Tabs')
                    ->columnSpanFull()
                    ->contained(false)
                    ->tabs([
                        // Tab 1: Thông tin quan trọng
                        Forms\Components\Tabs\Tab::make('Thông tin quan trọng')
                            ->icon('heroicon-o-information-circle')
                            ->schema([
                                Forms\Components\Section::make('Thông tin cơ bản')
                                    ->schema([
                                        Forms\Components\TextInput::make('title')
                                            ->label('Tiêu đề album')
                                            ->required()
                                            ->maxLength(255)
                                            ->live(onBlur: true)
                                            ->afterStateUpdated(fn (string $context, $state, Forms\Set $set) =>
                                                $context === 'create' ? $set('slug', Str::slug($state)) : null
                                            )
                                            ->helperText('Tên album sẽ hiển thị trên website'),

                                        Forms\Components\TextInput::make('slug')
                                            ->label('Đường dẫn (Slug)')
                                            ->maxLength(255)
                                            ->unique(Album::class, 'slug', ignoreRecord: true)
                                            ->rules(['alpha_dash'])
                                            ->suffixAction(
                                                Forms\Components\Actions\Action::make('generateSlug')
                                                    ->icon('heroicon-m-arrow-path')
                                                    ->action(function (Forms\Set $set, Forms\Get $get) {
                                                        $title = $get('title');
                                                        if ($title) {
                                                            $set('slug', Str::slug($title));
                                                        }
                                                    })
                                            )
                                            ->helperText('Để trống sẽ tự động tạo từ tiêu đề'),

                                        Forms\Components\Textarea::make('description')
                                            ->label('Mô tả album')
                                            ->rows(4)
                                            ->columnSpanFull()
                                            ->helperText('Mô tả ngắn gọn về nội dung album'),
                                    ])
                                    ->columns(2),

                                Forms\Components\Section::make('Loại nội dung Album')
                                    ->description('Chọn loại nội dung cho album này. Mỗi album chỉ có thể chứa một loại: PDF hoặc hình ảnh.')
                                    ->schema([
                                        Forms\Components\Radio::make('media_type')
                                            ->label('Loại nội dung')
                                            ->options([
                                                'pdf' => 'Tài liệu PDF',
                                                'images' => 'Hình ảnh',
                                            ])
                                            ->default('pdf')
                                            ->required()
                                            ->live()
                                            ->afterStateUpdated(function (Forms\Set $set, $state) {
                                                // Clear files khi chuyển media_type để tránh accumulation
                                                if ($state === 'pdf') {
                                                    $set('thumbnail', null); // Clear images
                                                } elseif ($state === 'images') {
                                                    $set('pdf_file', null); // Clear PDF
                                                    $set('total_pages', null);
                                                    $set('file_size', null);
                                                }

                                                // Force cache clear when media type changes
                                                try {
                                                    \App\Providers\ViewServiceProvider::forceRebuildAlbumsCache();
                                                } catch (\Exception $e) {
                                                    // Silent fail - cache will be cleared later
                                                }
                                            })
                                            ->columnSpanFull(),

                                        // PDF Upload - chỉ hiện khi chọn PDF
                                        Forms\Components\FileUpload::make('pdf_file')
                                            ->label('File PDF')
                                            ->acceptedFileTypes(['application/pdf'])
                                            ->directory('albums/pdfs')
                                            ->maxSize(50000) // 50MB
                                            ->downloadable()
                                            ->previewable(false)
                                            ->helperText('File PDF tài liệu. Kích thước tối đa: 50MB')
                                            ->visible(fn (Forms\Get $get): bool => $get('media_type') === 'pdf')
                                            ->required(fn (Forms\Get $get): bool => $get('media_type') === 'pdf')
                                            ->rules([
                                                fn (Forms\Get $get): \Closure => function (string $attribute, $value, \Closure $fail) use ($get) {
                                                    if ($get('media_type') === 'images' && $value) {
                                                        $fail('Không thể upload PDF khi đã chọn loại nội dung là hình ảnh.');
                                                    }
                                                },
                                            ])
                                            ->columnSpanFull(),

                                        // Image Upload - chỉ hiện khi chọn Images (hỗ trợ multiple files)
                                        Forms\Components\FileUpload::make('thumbnail')
                                            ->label('Hình ảnh Album')
                                            ->image()
                                            ->directory('albums/images')
                                            ->visibility('public')
                                            ->maxSize(5120) // 5MB per file
                                            ->imageEditor()
                                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                                            ->multiple() // Cho phép upload nhiều file
                                            ->maxFiles(10) // Tối đa 10 ảnh
                                            ->reorderable() // Cho phép sắp xếp lại thứ tự
                                            ->helperText('Upload nhiều hình ảnh cho album (tối đa 10 ảnh). Kích thước tối đa mỗi ảnh: 5MB. Ảnh đầu tiên sẽ là ảnh đại diện.')
                                            ->visible(fn (Forms\Get $get): bool => $get('media_type') === 'images')
                                            ->required(fn (Forms\Get $get): bool => $get('media_type') === 'images')
                                            ->rules([
                                                fn (Forms\Get $get): \Closure => function (string $attribute, $value, \Closure $fail) use ($get) {
                                                    if ($get('media_type') === 'pdf' && $value) {
                                                        $fail('Không thể upload hình ảnh khi đã chọn loại nội dung là PDF.');
                                                    }
                                                },
                                            ])
                                            ->afterStateUpdated(function ($state) {
                                                // Force cache refresh after image upload
                                                if (!empty($state)) {
                                                    try {
                                                        \App\Providers\ViewServiceProvider::forceRebuildAlbumsCache();
                                                    } catch (\Exception $e) {
                                                        // Silent fail
                                                    }
                                                }
                                            })
                                            ->columnSpanFull(),
                                    ]),

                                Forms\Components\Section::make('Cài đặt hiển thị')
                                    ->schema([
                                        Forms\Components\Select::make('status')
                                            ->label('Trạng thái')
                                            ->options([
                                                'active' => 'Hoạt động',
                                                'inactive' => 'Không hoạt động',
                                            ])
                                            ->default('active')
                                            ->required(),

                                        Forms\Components\DatePicker::make('published_date')
                                            ->label('Ngày xuất bản')
                                            ->default(now()),

                                        Forms\Components\TextInput::make('order')
                                            ->label('Thứ tự hiển thị')
                                            ->numeric()
                                            ->default(0)
                                            ->helperText('Số thứ tự sắp xếp (0 = đầu tiên)'),

                                        Forms\Components\Toggle::make('featured')
                                            ->label('Album nổi bật')
                                            ->default(false)
                                            ->helperText('Hiển thị trong timeline'),
                                    ])
                                    ->columns(2),
                            ]),

                        // Tab 2: Cài đặt nâng cao
                        Forms\Components\Tabs\Tab::make('Cài đặt nâng cao')
                            ->icon('heroicon-o-adjustments-horizontal')
                            ->schema([
                                Forms\Components\Section::make('Tối ưu SEO')
                                    ->description('Cấu hình SEO để tối ưu hóa công cụ tìm kiếm')
                                    ->schema([
                                        Forms\Components\TextInput::make('seo_title')
                                            ->label('Tiêu đề SEO')
                                            ->maxLength(255)
                                            ->helperText('Tiêu đề hiển thị trên Google (khuyến nghị: 50-60 ký tự)'),

                                        Forms\Components\Textarea::make('seo_description')
                                            ->label('Mô tả SEO')
                                            ->rows(3)
                                            ->maxLength(160)
                                            ->helperText('Mô tả hiển thị trên Google (khuyến nghị: 150-160 ký tự)')
                                            ->columnSpanFull(),

                                        Forms\Components\FileUpload::make('og_image_link')
                                            ->label('Hình ảnh OG (Social Media)')
                                            ->image()
                                            ->directory('albums/og-images')
                                            ->visibility('public')
                                            ->maxSize(5120)
                                            ->imageEditor()
                                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                                            ->helperText('Ảnh hiển thị khi chia sẻ trên mạng xã hội. Kích thước khuyến nghị: 1200x630px')
                                            ->saveUploadedFileUsing(function ($file, $get) {
                                                $title = $get('title') ?? 'album';
                                                $customName = 'og-album-' . $title;

                                                return \App\Actions\ConvertImageToWebp::run(
                                                    $file,
                                                    'albums/og-images',
                                                    $customName,
                                                    1200,
                                                    630
                                                );
                                            }),
                                    ])
                                    ->columns(2),


                            ]),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('order')
                    ->label('Thứ tự')
                    ->numeric()
                    ->sortable()
                    ->badge()
                    ->color('gray')
                    ->width('80px'),

                // Đã xóa thumbnail column vì không cần thiết

                Tables\Columns\TextColumn::make('title')
                    ->label('Tiêu đề')
                    ->searchable()
                    ->sortable()
                    ->limit(50),

                Tables\Columns\TextColumn::make('slug')
                    ->label('Slug')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('media_type')
                    ->label('Loại nội dung')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pdf' => 'danger',
                        'images' => 'primary',
                        default => 'gray',
                    })
                    ->icon(fn (string $state): string => match ($state) {
                        'pdf' => 'heroicon-o-document',
                        'images' => 'heroicon-o-photo',
                        default => 'heroicon-o-question-mark-circle',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'pdf' => 'PDF',
                        'images' => 'Hình ảnh',
                        default => $state,
                    }),

                Tables\Columns\TextColumn::make('published_date')
                    ->label('Ngày xuất bản')
                    ->date('d/m/Y')
                    ->sortable(),

                Tables\Columns\TextColumn::make('status')
                    ->label('Trạng thái')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'active' => 'success',
                        'inactive' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'active' => 'Hoạt động',
                        'inactive' => 'Không hoạt động',
                        default => $state,
                    }),

                Tables\Columns\IconColumn::make('featured')
                    ->label('Nổi bật')
                    ->boolean()
                    ->trueIcon('heroicon-o-star')
                    ->falseIcon('heroicon-o-star')
                    ->trueColor('warning')
                    ->falseColor('gray'),

                Tables\Columns\TextColumn::make('total_pages')
                    ->label('Số trang')
                    ->numeric()
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('view_count')
                    ->label('Lượt xem')
                    ->numeric()
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('download_count')
                    ->label('Lượt tải')
                    ->numeric()
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Ngày tạo')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Trạng thái')
                    ->options([
                        'active' => 'Hoạt động',
                        'inactive' => 'Không hoạt động',
                    ]),

                Tables\Filters\TernaryFilter::make('featured')
                    ->label('Nổi bật'),

                Tables\Filters\Filter::make('has_pdf')
                    ->label('Có PDF')
                    ->query(fn (Builder $query): Builder => $query->whereNotNull('pdf_file')),

                Tables\Filters\Filter::make('published')
                    ->label('Đã xuất bản')
                    ->query(fn (Builder $query): Builder => $query->where('published_date', '<=', now())),
            ])
            ->headerActions([
                // Đã xóa quản lý ảnh album vì không cần thiết
            ])
            ->actions([
                // Đã xóa action xem ảnh vì không cần thiết
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
            ->defaultSort('order', 'asc')
            ->reorderable('order');
    }

    public static function getRelations(): array
    {
        return [
            // Đã đơn giản hóa - không cần RelationManager cho images
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAlbums::route('/'),
            'create' => Pages\CreateAlbum::route('/create'),
            'edit' => Pages\EditAlbum::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return (string) static::getModel()::where('status', 'active')->count();
    }




}
