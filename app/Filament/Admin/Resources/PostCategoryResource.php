<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\PostCategoryResource\Pages;
use App\Models\CatPost;
use App\Traits\HasImageUpload;
use App\Traits\OptimizedFilamentResource;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class PostCategoryResource extends Resource
{
    use OptimizedFilamentResource, HasImageUpload;

    protected static ?string $model = CatPost::class;

    protected static ?string $navigationIcon = 'heroicon-o-folder';

    protected static ?string $navigationLabel = 'Danh mục bài viết';

    protected static ?string $modelLabel = 'danh mục bài viết';

    protected static ?string $pluralModelLabel = 'danh mục bài viết';

    protected static ?string $navigationGroup = 'Quản lý nội dung';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Thông tin cơ bản')
                    ->schema([
                        TextInput::make('name')
                            ->label('Tên danh mục')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (string $state, callable $set) => $set('slug', Str::slug($state))),

                        TextInput::make('slug')
                            ->label('Đường dẫn')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true)
                            ->rules(['alpha_dash']),

                        Textarea::make('description')
                            ->label('Mô tả')
                            ->rows(3)
                            ->columnSpanFull(),

                        Select::make('parent_id')
                            ->label('Danh mục cha')
                            ->relationship('parent', 'name')
                            ->searchable()
                            ->preload()
                            ->nullable(),

                        self::createImageUpload(
                            'image',
                            'Hình ảnh danh mục',
                            'categories',
                            800,
                            600
                        )->imagePreviewHeight('150'),
                    ])->columns(2),

                Section::make('SEO & Hiển thị')
                    ->schema([
                        TextInput::make('seo_title')
                            ->label('Tiêu đề SEO')
                            ->maxLength(255),

                        Textarea::make('seo_description')
                            ->label('Mô tả SEO')
                            ->rows(3),

                        TextInput::make('og_image_link')
                            ->label('Link ảnh OG')
                            ->url()
                            ->maxLength(255),

                        TextInput::make('order')
                            ->label('Thứ tự hiển thị')
                            ->numeric()
                            ->default(0)
                            ->required(),

                        Select::make('status')
                            ->label('Trạng thái')
                            ->options([
                                'active' => 'Hiển thị',
                                'inactive' => 'Ẩn',
                            ])
                            ->default('active')
                            ->required(),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable()
                    ->searchable(),

                ImageColumn::make('image')
                    ->label('Hình ảnh')
                    ->circular()
                    ->defaultImageUrl(asset('images/placeholder-category.jpg')),

                TextColumn::make('name')
                    ->label('Tên danh mục')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('slug')
                    ->label('Đường dẫn')
                    ->searchable()
                    ->copyable()
                    ->copyMessage('Đã sao chép!')
                    ->color('gray'),

                TextColumn::make('parent.name')
                    ->label('Danh mục cha')
                    ->sortable()
                    ->placeholder('Danh mục gốc'),

                TextColumn::make('posts_count')
                    ->label('Số bài viết')
                    ->counts('posts')
                    ->sortable()
                    ->badge()
                    ->color('success'),

                TextColumn::make('order')
                    ->label('Thứ tự')
                    ->sortable()
                    ->alignCenter(),

                SelectColumn::make('status')
                    ->label('Trạng thái')
                    ->options([
                        'active' => 'Hiển thị',
                        'inactive' => 'Ẩn',
                    ])
                    ->selectablePlaceholder(false),

                TextColumn::make('created_at')
                    ->label('Ngày tạo')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Trạng thái')
                    ->options([
                        'active' => 'Hiển thị',
                        'inactive' => 'Ẩn',
                    ]),

                Tables\Filters\SelectFilter::make('parent_id')
                    ->label('Danh mục cha')
                    ->relationship('parent', 'name')
                    ->searchable()
                    ->preload(),
            ])
            ->actions([
                Tables\Actions\Action::make('view_frontend')
                    ->label('Xem trên website')
                    ->icon('heroicon-o-eye')
                    ->color('info')
                    ->url(fn ($record) => route('posts.category', $record->slug))
                    ->openUrlInNewTab(),
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPostCategories::route('/'),
            'create' => Pages\CreatePostCategory::route('/create'),
            'edit' => Pages\EditPostCategory::route('/{record}/edit'),
        ];
    }

    /**
     * Lấy danh sách cột cần thiết cho table
     */
    protected static function getTableColumns(): array
    {
        return [
            'id',
            'name',
            'slug',
            'description',
            'parent_id',
            'status',
            'order',
            'image',
            'created_at'
        ];
    }

    /**
     * Lấy relationships cần thiết cho form
     */
    protected static function getFormRelationships(): array
    {
        return [
            'parent' => function($query) {
                $query->select(['id', 'name', 'slug']);
            },
            'posts' => function($query) {
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
        return ['name', 'description'];
    }
}
