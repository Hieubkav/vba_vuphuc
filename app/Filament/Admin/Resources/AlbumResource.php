<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\AlbumResource\Pages;
use App\Filament\Admin\Resources\AlbumResource\RelationManagers;
use App\Filament\Admin\Resources\AlbumImageResource;
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

    protected static ?string $navigationLabel = 'Album khóa học';

    protected static ?string $modelLabel = 'album khóa học';

    protected static ?string $pluralModelLabel = 'album khóa học';

    protected static ?string $navigationGroup = 'Quản lý khóa học';

    protected static ?int $navigationSort = 5;

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

                                Forms\Components\Section::make('File và Media')
                                    ->schema([
                                        Forms\Components\FileUpload::make('thumbnail')
                                            ->label('Ảnh thumbnail')
                                            ->image()
                                            ->directory('albums/thumbnails')
                                            ->visibility('public')
                                            ->maxSize(5120)
                                            ->imageEditor()
                                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                                            ->helperText('Ảnh đại diện cho album. Kích thước tối đa: 5MB')
                                            ->saveUploadedFileUsing(function ($file, $get) {
                                                $title = $get('title') ?? 'album';
                                                $customName = 'album-' . $title;

                                                return \App\Actions\ConvertImageToWebp::run(
                                                    $file,
                                                    'albums/thumbnails',
                                                    $customName,
                                                    800,
                                                    600
                                                );
                                            }),

                                        Forms\Components\FileUpload::make('pdf_file')
                                            ->label('File PDF')
                                            ->acceptedFileTypes(['application/pdf'])
                                            ->directory('albums/pdfs')
                                            ->maxSize(50000) // 50MB
                                            ->downloadable()
                                            ->previewable(false)
                                            ->helperText('File PDF tài liệu (tùy chọn). Kích thước tối đa: 50MB'),
                                    ])
                                    ->columns(2),

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
                                            ->helperText('Hiển thị trong danh sách album nổi bật'),
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

                Tables\Columns\ImageColumn::make('thumbnail')
                    ->label('Thumbnail')
                    ->circular()
                    ->size(50),

                Tables\Columns\TextColumn::make('title')
                    ->label('Tiêu đề')
                    ->searchable()
                    ->sortable()
                    ->limit(50),

                Tables\Columns\TextColumn::make('slug')
                    ->label('Slug')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\IconColumn::make('pdf_file')
                    ->label('PDF')
                    ->boolean()
                    ->trueIcon('heroicon-o-document')
                    ->falseIcon('heroicon-o-x-mark')
                    ->trueColor('success')
                    ->falseColor('danger'),

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
                Tables\Actions\Action::make('manage_images')
                    ->label('Quản lý ảnh album')
                    ->icon('heroicon-o-photo')
                    ->color('info')
                    ->url(fn () => AlbumImageResource::getUrl('index', [
                        'tableFilters[album_id][value]' => null
                    ])),
            ])
            ->actions([
                Tables\Actions\Action::make('view_images')
                    ->label('Xem ảnh')
                    ->icon('heroicon-o-photo')
                    ->color('info')
                    ->url(fn ($record) => AlbumImageResource::getUrl('index', [
                        'tableFilters[album_id][value]' => $record->id
                    ])),
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
            RelationManagers\AlbumImagesRelationManager::class,
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
