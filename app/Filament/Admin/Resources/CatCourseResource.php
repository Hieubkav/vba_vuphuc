<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\CatCourseResource\Pages;
use App\Filament\Admin\Resources\CatCourseResource\RelationManagers;
use App\Filament\Admin\Resources\CourseResource;
use App\Models\CatCourse;
use App\Traits\HasImageUpload;
use App\Traits\SimpleFilamentOptimization;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class CatCourseResource extends Resource
{
    use HasImageUpload, SimpleFilamentOptimization;

    protected static ?string $model = CatCourse::class;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';

    protected static ?string $navigationLabel = 'Danh mục khóa học';

    protected static ?string $modelLabel = 'Danh mục khóa học';

    protected static ?string $pluralModelLabel = 'Danh mục khóa học';

    protected static ?string $navigationGroup = 'Quản lý khóa học';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Thông tin cơ bản')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Tên danh mục')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (string $context, $state, callable $set) =>
                                $context === 'create' ? $set('slug', Str::slug($state)) : null
                            ),
                        Forms\Components\TextInput::make('slug')
                            ->label('Slug')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true)
                            ->rules(['alpha_dash']),
                        Forms\Components\Textarea::make('description')
                            ->label('Mô tả')
                            ->rows(3)
                            ->columnSpanFull(),
                    ])->columns(2),

                Forms\Components\Section::make('Hiển thị & Thiết kế')
                    ->schema([
                        self::createThumbnailUpload(
                            'image',
                            'Hình ảnh danh mục',
                            'cat-courses',
                            600,
                            400
                        )->columnSpanFull(),
                        Forms\Components\ColorPicker::make('color')
                            ->label('Màu sắc đại diện')
                            ->default('#dc2626'),
                        Forms\Components\Select::make('icon')
                            ->label('Icon')
                            ->options([
                                'excel' => 'Excel',
                                'calculator' => 'Calculator',
                                'users' => 'Users',
                                'computer' => 'Computer',
                                'chart' => 'Chart',
                                'heart' => 'Heart',
                                'code' => 'Code',
                                'megaphone' => 'Megaphone',
                            ])
                            ->searchable(),
                    ])->columns(2),

                Forms\Components\Section::make('Cấu trúc & Trạng thái')
                    ->schema([
                        Forms\Components\Select::make('parent_id')
                            ->label('Danh mục cha')
                            ->relationship('parent', 'name')
                            ->searchable()
                            ->preload(),
                        Forms\Components\TextInput::make('order')
                            ->label('Thứ tự hiển thị')
                            ->numeric()
                            ->default(0)
                            ->minValue(0),
                        Forms\Components\Select::make('status')
                            ->label('Trạng thái')
                            ->options([
                                'active' => 'Hiển thị',
                                'inactive' => 'Ẩn',
                            ])
                            ->default('active')
                            ->required(),
                    ])->columns(3),

                Forms\Components\Section::make('SEO')
                    ->schema([
                        Forms\Components\TextInput::make('seo_title')
                            ->label('Tiêu đề SEO')
                            ->maxLength(255)
                            ->helperText('Để trống sẽ tự động sử dụng tên danh mục'),
                        Forms\Components\Textarea::make('seo_description')
                            ->label('Mô tả SEO')
                            ->rows(3)
                            ->maxLength(160)
                            ->helperText('Tối đa 160 ký tự'),
                        Forms\Components\FileUpload::make('og_image_link')
                            ->label('Hình ảnh OG')
                            ->image()
                            ->directory('cat-courses/og-images')
                            ->helperText('Hình ảnh hiển thị khi chia sẻ trên mạng xã hội'),
                    ])->columns(2)->collapsible(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image')
                    ->label('Ảnh')
                    ->circular()
                    ->size(40),

                Tables\Columns\TextColumn::make('name')
                    ->label('Tên danh mục')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->description(fn ($record): string =>
                        ($record->parent ? "Thuộc: {$record->parent->name}" : '') .
                        ($record->icon ? " • Icon: {$record->icon}" : '')
                    ),

                Tables\Columns\TextColumn::make('courses_count')
                    ->label('Khóa học')
                    ->counts('courses')
                    ->badge()
                    ->color('success')
                    ->alignCenter()
                    ->width(100),

                Tables\Columns\TextColumn::make('order')
                    ->label('Thứ tự')
                    ->numeric()
                    ->sortable()
                    ->alignCenter()
                    ->width(80),

                Tables\Columns\SelectColumn::make('status')
                    ->label('Trạng thái')
                    ->options([
                        'active' => 'Hiển thị',
                        'inactive' => 'Ẩn',
                    ])
                    ->selectablePlaceholder(false)
                    ->width(120),

                // Các cột ẩn mặc định - có thể bật lại khi cần
                Tables\Columns\TextColumn::make('slug')
                    ->label('Slug')
                    ->searchable()
                    ->copyable()
                    ->color('gray')
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\ColorColumn::make('color')
                    ->label('Màu sắc')
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('icon')
                    ->label('Icon')
                    ->badge()
                    ->color('info')
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('parent.name')
                    ->label('Danh mục cha')
                    ->sortable()
                    ->placeholder('Không có')
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
                    ->url(fn ($record) => route('courses.cat-category', $record->slug))
                    ->openUrlInNewTab(),
                Tables\Actions\Action::make('manage_courses')
                    ->label('Quản lý khóa học')
                    ->icon('heroicon-o-academic-cap')
                    ->color('success')
                    ->url(fn ($record) => CourseResource::getUrl('index', ['tableFilters[cat_course_id][value]' => $record->id]))
                    ->visible(fn ($record) => $record->courses_count > 0),
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
            ->reorderable('order')
            ->paginationPageOptions([10, 25, 50]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\CoursesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCatCourses::route('/'),
            'create' => Pages\CreateCatCourse::route('/create'),
            'edit' => Pages\EditCatCourse::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return (string) static::getModel()::where('status', 'active')->count();
    }

    public static function getNavigationBadgeColor(): string|array|null
    {
        return 'success';
    }

    /**
     * Lấy danh sách cột cần thiết cho table
     */
    protected static function getTableColumns(): array
    {
        return array (
  0 => 'id',
  1 => 'name',
  2 => 'slug',
  3 => 'description',
  4 => 'status',
  5 => 'order',
  6 => 'created_at',
);
    }

    /**
     * Lấy relationships cần thiết cho form
     */
    protected static function getFormRelationships(): array
    {
        return [
            'courses' => function($query) {
                $query->select(['id,title,cat_course_id']);
            }
        ];
    }

    /**
     * Lấy các cột có thể search
     */
    protected static function getSearchableColumns(): array
    {
        return array (
  0 => 'name',
  1 => 'description',
);
    }
}