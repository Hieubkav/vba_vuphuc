<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\AlbumResource\Pages;
use App\Filament\Admin\Resources\AlbumResource\RelationManagers;
use App\Models\Album;
use App\Traits\HasImageUpload;
use App\Traits\OptimizedFilamentResource;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;

class AlbumResource extends Resource
{
    use HasImageUpload, OptimizedFilamentResource;

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
                Forms\Components\Section::make('Thông tin cơ bản')
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->label('Tiêu đề')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (string $context, $state, Forms\Set $set) =>
                                $context === 'create' ? $set('slug', Str::slug($state)) : null
                            ),

                        Forms\Components\TextInput::make('slug')
                            ->label('Slug')
                            ->required()
                            ->maxLength(255)
                            ->unique(Album::class, 'slug', ignoreRecord: true)
                            ->rules(['alpha_dash']),

                        Forms\Components\Textarea::make('description')
                            ->label('Mô tả')
                            ->rows(3)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('File và Media')
                    ->schema([
                        Forms\Components\FileUpload::make('pdf_file')
                            ->label('File PDF')
                            ->acceptedFileTypes(['application/pdf'])
                            ->directory('albums/pdfs')
                            ->maxSize(50000) // 50MB
                            ->downloadable()
                            ->previewable(false),

                        self::createThumbnailUpload(
                            'thumbnail',
                            'Ảnh thumbnail',
                            'albums/thumbnails',
                            800,
                            600
                        ),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Cài đặt')
                    ->schema([
                        Forms\Components\DatePicker::make('published_date')
                            ->label('Ngày xuất bản')
                            ->default(now()),

                        Forms\Components\Select::make('status')
                            ->label('Trạng thái')
                            ->options([
                                'active' => 'Hoạt động',
                                'inactive' => 'Không hoạt động',
                            ])
                            ->default('active')
                            ->required(),

                        Forms\Components\TextInput::make('order')
                            ->label('Thứ tự')
                            ->numeric()
                            ->default(0),

                        Forms\Components\Toggle::make('featured')
                            ->label('Nổi bật')
                            ->default(false),

                        Forms\Components\TextInput::make('total_pages')
                            ->label('Tổng số trang')
                            ->numeric()
                            ->minValue(1),
                    ])
                    ->columns(3),

                Forms\Components\Section::make('SEO')
                    ->schema([
                        Forms\Components\TextInput::make('seo_title')
                            ->label('Tiêu đề SEO')
                            ->maxLength(255),

                        Forms\Components\Textarea::make('seo_description')
                            ->label('Mô tả SEO')
                            ->rows(3)
                            ->maxLength(500),

                        Forms\Components\TextInput::make('og_image_link')
                            ->label('Link ảnh OG')
                            ->url()
                            ->maxLength(255),
                    ])
                    ->columns(1)
                    ->collapsible(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
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

                Tables\Columns\TextColumn::make('order')
                    ->label('Thứ tự')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

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
            ->defaultSort('order')
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            // RelationManagers\ImagesRelationManager::class,
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
        $optimizationService = app(\App\Services\FilamentOptimizationService::class);

        return $optimizationService->cacheQuery(
            'albums_count_badge',
            function() {
                return static::getModel()::where('status', 'active')->count();
            },
            300 // Cache 5 phút
        );
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
            'status',
            'featured',
            'thumbnail',
            'pdf_file',
            'published_date',
            'total_pages',
            'view_count',
            'download_count',
            'order',
            'created_at'
        ];
    }

    /**
     * Lấy relationships cần thiết cho form
     */
    protected static function getFormRelationships(): array
    {
        return [
            'images' => function($query) {
                $query->where('status', 'active')
                      ->orderBy('order')
                      ->limit(20);
            }
        ];
    }

    /**
     * Lấy các cột có thể search
     */
    protected static function getSearchableColumns(): array
    {
        return ['title', 'description'];
    }
}
