<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\CatCourseResource\Pages;
use App\Filament\Admin\Resources\CatCourseResource\RelationManagers;
use App\Filament\Admin\Resources\CourseResource;
use App\Models\CatCourse;
// Bỏ traits để đơn giản hóa
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class CatCourseResource extends Resource
{
    // Bỏ hết traits để đơn giản hóa

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
                            ->maxLength(255)
                            ->unique(ignoreRecord: true)
                            ->rules(['alpha_dash'])
                            ->helperText('Để trống sẽ tự động tạo từ tên danh mục khi lưu')
                            ->suffixActions([
                                Forms\Components\Actions\Action::make('generateSlug')
                                    ->icon('heroicon-m-arrow-path')
                                    ->tooltip('Tự động tạo slug')
                                    ->action(function ($get, $set) {
                                        $name = $get('name');
                                        if ($name) {
                                            $set('slug', Str::slug($name));
                                        }
                                    })
                            ]),
                        Forms\Components\Textarea::make('description')
                            ->label('Mô tả')
                            ->rows(3)
                            ->columnSpanFull(),
                    ])->columns(2),

                Forms\Components\Section::make('Hình ảnh')
                    ->schema([
                        Forms\Components\FileUpload::make('image')
                            ->label('Hình ảnh danh mục')
                            ->image()
                            ->directory('cat-courses')
                            ->visibility('public')
                            ->maxSize(5120)
                            ->imageEditor()
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                            ->helperText('Chọn ảnh định dạng JPG, PNG hoặc WebP. Kích thước tối đa: 5MB')
                            ->saveUploadedFileUsing(function ($file, $get) {
                                $name = $get('name') ?? 'category';
                                $customName = 'cat-course-' . $name;

                                return \App\Actions\ConvertImageToWebp::run(
                                    $file,
                                    'cat-courses',
                                    $customName,
                                    600,
                                    400
                                );
                            })
                            ->columnSpanFull(),
                    ]),

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
                            ->helperText('Để trống sẽ tự động tạo: "[Tên danh mục] - Khóa học VBA Vũ Phúc"'),
                        Forms\Components\Textarea::make('seo_description')
                            ->label('Mô tả SEO')
                            ->rows(3)
                            ->maxLength(160)
                            ->helperText('Để trống sẽ tự động tạo từ mô tả danh mục hoặc tạo mô tả mặc định. Tối đa 160 ký tự.'),
                        Forms\Components\FileUpload::make('og_image_link')
                            ->label('Hình ảnh OG')
                            ->image()
                            ->directory('cat-courses/og-images')
                            ->helperText('Để trống sẽ tự động sử dụng hình ảnh danh mục. Hình ảnh hiển thị khi chia sẻ trên mạng xã hội.')
                            ->saveUploadedFileUsing(function ($file, $get) {
                                $name = $get('name') ?? 'category';
                                $customName = 'og-cat-course-' . $name;

                                return \App\Actions\ConvertImageToWebp::run(
                                    $file,
                                    'cat-courses/og-images',
                                    $customName,
                                    1200,
                                    630
                                );
                            }),
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
                        $record->parent ? "Thuộc: {$record->parent->name}" : ''
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
                    ->url(fn ($record) => route('courses.index', ['category' => $record->slug]))
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