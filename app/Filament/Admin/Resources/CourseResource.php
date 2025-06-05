<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\CourseResource\Pages;
use App\Filament\Admin\Resources\CourseResource\RelationManagers;
use App\Filament\Admin\Resources\CatCourseResource;
use App\Filament\Admin\Resources\InstructorResource;
use App\Models\Course;
use App\Traits\HasImageUpload;
use App\Traits\OptimizedFilamentResource;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Repeater;
use Filament\Support\Enums\FontWeight;
use Illuminate\Support\Str;

class CourseResource extends Resource
{
    use HasImageUpload, OptimizedFilamentResource;

    protected static ?string $model = Course::class;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';

    protected static ?string $navigationLabel = 'Khóa học';

    protected static ?string $modelLabel = 'khóa học';

    protected static ?string $pluralModelLabel = 'khóa học';

    protected static ?string $navigationGroup = 'Quản lý khóa học';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Thông tin cơ bản - luôn hiển thị
                Section::make('📚 Thông tin cơ bản')
                    ->description('Thông tin chính của khóa học')
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->label('Tiêu đề khóa học')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (string $context, $state, Forms\Set $set) =>
                                $context === 'create' ? $set('slug', Str::slug($state)) : null
                            ),

                        Forms\Components\TextInput::make('slug')
                            ->label('Đường dẫn (Slug)')
                            ->required()
                            ->maxLength(255)
                            ->unique(Course::class, 'slug', ignoreRecord: true)
                            ->rules(['alpha_dash']),

                        Forms\Components\Select::make('cat_course_id')
                            ->label('Danh mục khóa học')
                            ->relationship('courseCategory', 'name')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->helperText('Chọn danh mục khóa học chính'),

                        Forms\Components\Select::make('category_id')
                            ->label('Danh mục bài viết')
                            ->relationship('category', 'name')
                            ->searchable()
                            ->preload()
                            ->helperText('Chọn để hiển thị khóa học trong blog')
                            ->createOptionForm([
                                Forms\Components\TextInput::make('name')
                                    ->label('Tên danh mục')
                                    ->required(),
                                Forms\Components\TextInput::make('slug')
                                    ->label('Slug')
                                    ->required(),
                            ]),
                    ])->columns(2),

                // Mô tả khóa học
                Section::make('📝 Mô tả khóa học')
                    ->description('Nội dung chi tiết về khóa học')
                    ->schema([
                        Forms\Components\RichEditor::make('description')
                            ->label('Mô tả chi tiết')
                            ->required()
                            ->columnSpanFull()
                            ->toolbarButtons([
                                'bold', 'italic', 'underline', 'strike',
                                'bulletList', 'orderedList', 'h2', 'h3',
                                'link', 'blockquote', 'codeBlock'
                            ]),
                    ]),

                // Hình ảnh
                Section::make('🖼️ Hình ảnh')
                    ->description('Hình đại diện cho khóa học')
                    ->schema([
                        self::createThumbnailUpload(
                            'thumbnail',
                            'Hình đại diện',
                            'courses/thumbnails',
                            800,
                            450
                        ),
                    ])
                    ->collapsible(),

                // Giá & Thời gian
                Section::make('💰 Giá & Thời gian')
                    ->description('Thông tin về giá cả và thời gian khóa học')
                    ->schema([
                        Forms\Components\TextInput::make('price')
                            ->label('Giá khóa học (VNĐ)')
                            ->required()
                            ->numeric()
                            ->default(0)
                            ->suffix('VNĐ'),

                        Forms\Components\TextInput::make('compare_price')
                            ->label('Giá so sánh (VNĐ)')
                            ->numeric()
                            ->suffix('VNĐ')
                            ->helperText('Giá gốc để hiển thị khuyến mãi'),

                        Forms\Components\TextInput::make('duration_hours')
                            ->label('Thời lượng (giờ)')
                            ->required()
                            ->numeric()
                            ->default(0)
                            ->suffix('giờ'),

                        Forms\Components\TextInput::make('max_students')
                            ->label('Số học viên tối đa')
                            ->numeric()
                            ->helperText('Để trống nếu không giới hạn'),

                        Forms\Components\DatePicker::make('start_date')
                            ->label('Ngày bắt đầu'),

                        Forms\Components\DatePicker::make('end_date')
                            ->label('Ngày kết thúc'),
                    ])->columns(3)
                    ->collapsible(),

                // Cấu hình cơ bản
                Section::make('⚙️ Cấu hình cơ bản')
                    ->description('Cấp độ, trạng thái và thứ tự hiển thị')
                    ->schema([
                        Forms\Components\Select::make('level')
                            ->label('Cấp độ')
                            ->required()
                            ->options([
                                'beginner' => 'Cơ bản',
                                'intermediate' => 'Trung cấp',
                                'advanced' => 'Nâng cao',
                            ])
                            ->default('beginner'),

                        Forms\Components\Select::make('status')
                            ->label('Trạng thái')
                            ->required()
                            ->options([
                                'draft' => 'Nháp',
                                'active' => 'Hoạt động',
                                'inactive' => 'Tạm dừng',
                            ])
                            ->default('draft'),

                        Forms\Components\Toggle::make('is_featured')
                            ->label('Khóa học nổi bật')
                            ->helperText('Hiển thị trong danh sách khóa học nổi bật'),

                        Forms\Components\TextInput::make('order')
                            ->label('Thứ tự sắp xếp')
                            ->numeric()
                            ->default(0)
                            ->helperText('Số nhỏ hơn sẽ hiển thị trước'),
                    ])->columns(2),

                // Thông tin giảng viên
                Section::make('👨‍🏫 Thông tin giảng viên')
                    ->description('Chọn giảng viên cho khóa học')
                    ->schema([
                        Forms\Components\Select::make('instructor_id')
                            ->label('Giảng viên')
                            ->relationship('instructor', 'name')
                            ->searchable()
                            ->preload()
                            ->helperText('Chọn giảng viên phụ trách khóa học này')
                            ->createOptionForm([
                                Forms\Components\TextInput::make('name')
                                    ->label('Họ và tên')
                                    ->required(),
                                Forms\Components\TextInput::make('email')
                                    ->label('Email')
                                    ->email()
                                    ->required(),
                                Forms\Components\TextInput::make('specialization')
                                    ->label('Chuyên môn'),
                            ]),
                    ])
                    ->collapsible(),

                // Yêu cầu & Mục tiêu
                Section::make('🎯 Yêu cầu & Mục tiêu')
                    ->description('Yêu cầu đầu vào và mục tiêu học tập')
                    ->schema([
                        Repeater::make('requirements')
                            ->label('Yêu cầu đầu vào')
                            ->schema([
                                Forms\Components\TextInput::make('requirement')
                                    ->label('Yêu cầu')
                                    ->required(),
                            ])
                            ->defaultItems(1)
                            ->addActionLabel('Thêm yêu cầu')
                            ->collapsible()
                            ->mutateDehydratedStateUsing(function ($state) {
                                // Lọc bỏ các item rỗng trước khi lưu
                                if (is_array($state)) {
                                    return array_values(array_filter($state, function ($item) {
                                        return !empty($item['requirement'] ?? '');
                                    }));
                                }
                                return $state;
                            }),

                        Repeater::make('what_you_learn')
                            ->label('Những gì học được')
                            ->schema([
                                Forms\Components\TextInput::make('learning_outcome')
                                    ->label('Kết quả học tập')
                                    ->required(),
                            ])
                            ->defaultItems(1)
                            ->addActionLabel('Thêm mục tiêu')
                            ->collapsible()
                            ->mutateDehydratedStateUsing(function ($state) {
                                // Lọc bỏ các item rỗng trước khi lưu
                                if (is_array($state)) {
                                    return array_values(array_filter($state, function ($item) {
                                        return !empty($item['learning_outcome'] ?? '');
                                    }));
                                }
                                return $state;
                            }),
                    ])
                    ->collapsible(),

                // SEO
                Section::make('🔍 SEO & Social Media')
                    ->description('Tối ưu hóa công cụ tìm kiếm và mạng xã hội')
                    ->schema([
                        Forms\Components\TextInput::make('seo_title')
                            ->label('Tiêu đề SEO')
                            ->maxLength(60)
                            ->helperText('Tối đa 60 ký tự. Để trống sẽ dùng tiêu đề khóa học'),

                        Forms\Components\Textarea::make('seo_description')
                            ->label('Mô tả SEO')
                            ->maxLength(160)
                            ->rows(3)
                            ->helperText('Tối đa 160 ký tự. Để trống sẽ tự động tạo từ mô tả'),

                        self::createImageUpload(
                            'og_image_link',
                            'Hình ảnh OG (Social Media)',
                            'courses/og-images',
                            1200,
                            630,
                            5120,
                            'Kích thước khuyến nghị: 1200x630px. Để trống sẽ dùng hình đại diện',
                            ['16:9'],
                            false,
                            false
                        ),
                    ])
                    ->collapsible()
                    ->collapsed(),

                // Liên kết & Form
                Section::make('🔗 Liên kết & Form')
                    ->description('Google Form và nhóm học tập')
                    ->schema([
                        Forms\Components\TextInput::make('gg_form')
                            ->label('Link Google Form')
                            ->url()
                            ->helperText('Link đăng ký khóa học qua Google Form'),

                        Forms\Components\TextInput::make('group_link')
                            ->label('Link nhóm học tập')
                            ->url()
                            ->helperText('Link tham gia nhóm học tập (Telegram, Zalo, etc.)'),

                        Forms\Components\Toggle::make('show_form_link')
                            ->label('Hiển thị nút đăng ký')
                            ->helperText('Hiển thị nút đăng ký Google Form trên giao diện'),

                        Forms\Components\Toggle::make('show_group_link')
                            ->label('Hiển thị nút tham gia nhóm')
                            ->helperText('Hiển thị nút tham gia nhóm học tập trên giao diện'),

                        Forms\Components\Toggle::make('show_instructor')
                            ->label('Hiển thị giảng viên')
                            ->default(true)
                            ->helperText('Ẩn/hiện thông tin giảng viên trong giao diện'),

                        Forms\Components\Toggle::make('show_price')
                            ->label('Hiển thị giá')
                            ->default(true)
                            ->helperText('Ẩn/hiện giá khóa học trong giao diện'),
                    ])->columns(2)
                    ->collapsible()
                    ->collapsed(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('thumbnail')
                    ->label('Ảnh')
                    ->circular()
                    ->size(50),

                Tables\Columns\TextColumn::make('title')
                    ->label('Khóa học')
                    ->searchable()
                    ->sortable()
                    ->weight(FontWeight::Bold)
                    ->description(fn (Course $record): string =>
                        ($record->courseCategory ? "📚 {$record->courseCategory->name}" : '❌ Chưa phân loại') .
                        ($record->instructor && $record->show_instructor ? " • 👨‍🏫 {$record->instructor->name}" : '')
                    ),

                Tables\Columns\TextColumn::make('courseCategory.name')
                    ->label('Danh mục')
                    ->badge()
                    ->color(fn ($record) => $record->courseCategory ? 'success' : 'danger')
                    ->formatStateUsing(fn ($state) => $state ?: 'Chưa phân loại')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('price')
                    ->label('Giá')
                    ->money('VND')
                    ->sortable()
                    ->alignCenter()
                    ->width(120),

                Tables\Columns\TextColumn::make('students_count')
                    ->label('Học viên')
                    ->counts('students')
                    ->getStateUsing(function (Course $record): string {
                        $studentsCount = $record->students()->count();
                        return $studentsCount . ($record->max_students ? '/' . $record->max_students : '');
                    })
                    ->badge()
                    ->color(function (Course $record): string {
                        $studentsCount = $record->students()->count();
                        $maxStudents = $record->max_students;
                        if ($maxStudents && $studentsCount >= $maxStudents) {
                            return 'danger';
                        }
                        return $studentsCount > 0 ? 'success' : 'gray';
                    })
                    ->alignCenter()
                    ->width(100),

                Tables\Columns\TextColumn::make('level')
                    ->label('Cấp độ')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'beginner' => 'success',
                        'intermediate' => 'warning',
                        'advanced' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'beginner' => 'Cơ bản',
                        'intermediate' => 'Trung cấp',
                        'advanced' => 'Nâng cao',
                        default => $state,
                    })
                    ->width(100),

                Tables\Columns\TextColumn::make('status')
                    ->label('Trạng thái')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'active' => 'success',
                        'inactive' => 'warning',
                        'draft' => 'gray',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'active' => 'Hoạt động',
                        'inactive' => 'Tạm dừng',
                        'draft' => 'Nháp',
                        default => $state,
                    })
                    ->width(120),

                // Cột ẩn mặc định

                Tables\Columns\TextColumn::make('instructor.name')
                    ->label('Giảng viên')
                    ->searchable()
                    ->badge()
                    ->color('info')
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('duration_hours')
                    ->label('Thời lượng')
                    ->suffix(' giờ')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\IconColumn::make('is_featured')
                    ->label('Nổi bật')
                    ->boolean()
                    ->trueIcon('heroicon-o-star')
                    ->falseIcon('heroicon-o-star')
                    ->trueColor('warning')
                    ->falseColor('gray')
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('category.name')
                    ->label('Danh mục bài viết')
                    ->badge()
                    ->color('info')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('start_date')
                    ->label('Ngày bắt đầu')
                    ->date('d/m/Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Ngày tạo')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('cat_course_id')
                    ->label('Danh mục khóa học')
                    ->relationship('courseCategory', 'name')
                    ->preload(),

                Tables\Filters\SelectFilter::make('instructor_id')
                    ->label('Giảng viên')
                    ->relationship('instructor', 'name')
                    ->preload(),

                Tables\Filters\SelectFilter::make('level')
                    ->label('Cấp độ')
                    ->options([
                        'beginner' => 'Cơ bản',
                        'intermediate' => 'Trung cấp',
                        'advanced' => 'Nâng cao',
                    ]),

                Tables\Filters\SelectFilter::make('status')
                    ->label('Trạng thái')
                    ->options([
                        'draft' => 'Nháp',
                        'active' => 'Hoạt động',
                        'inactive' => 'Tạm dừng',
                    ]),

                Tables\Filters\TernaryFilter::make('is_featured')
                    ->label('Nổi bật'),

                // Filters ẩn mặc định
                Tables\Filters\SelectFilter::make('category_id')
                    ->label('Danh mục bài viết')
                    ->relationship('category', 'name')
                    ->preload(),
            ])
            ->actions([
                Tables\Actions\Action::make('view_frontend')
                    ->label('Xem trên website')
                    ->icon('heroicon-o-eye')
                    ->color('info')
                    ->url(fn ($record) => route('courses.show', $record->slug))
                    ->openUrlInNewTab(),
                Tables\Actions\Action::make('edit_instructor')
                    ->label('Sửa giảng viên')
                    ->icon('heroicon-o-user')
                    ->color('success')
                    ->url(fn ($record) => $record->instructor ?
                        InstructorResource::getUrl('edit', ['record' => $record->instructor->id]) : null)
                    ->visible(fn ($record) => $record->instructor !== null),
                Tables\Actions\Action::make('edit_category')
                    ->label('Sửa danh mục')
                    ->icon('heroicon-o-folder')
                    ->color('warning')
                    ->url(fn ($record) => $record->courseCategory ?
                        CatCourseResource::getUrl('edit', ['record' => $record->courseCategory->id]) : null)
                    ->visible(fn ($record) => $record->courseCategory !== null),
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
            ->reorderable('order')
            ->defaultSort('order', 'asc');
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\ImagesRelationManager::class,
            // RelationManagers\MaterialsRelationManager::class,
            // RelationManagers\StudentsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCourses::route('/'),
            'create' => Pages\CreateCourse::route('/create'),
            'edit' => Pages\EditCourse::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        $optimizationService = app(\App\Services\FilamentOptimizationService::class);

        return $optimizationService->cacheQuery(
            'courses_count_badge',
            function() {
                return static::getModel()::where('status', 'active')->count();
            },
            300 // Cache 5 phút
        );
    }

    public static function getNavigationBadgeColor(): string|array|null
    {
        return 'primary';
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
            'cat_course_id',
            'instructor_id',
            'category_id',
            'price',
            'level',
            'status',
            'is_featured',
            'thumbnail',
            'max_students',
            'created_at'
        ];
    }

    /**
     * Lấy relationships cần thiết cho form
     */
    protected static function getFormRelationships(): array
    {
        return [
            'courseCategory' => function($query) {
                $query->select(['id', 'name', 'slug']);
            },
            'instructor' => function($query) {
                $query->select(['id', 'name', 'email', 'specialization']);
            },
            'category' => function($query) {
                $query->select(['id', 'name', 'slug']);
            },
            'images' => function($query) {
                $query->where('status', 'active')
                      ->orderBy('is_main', 'desc')
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
        return ['title', 'description'];
    }
}
