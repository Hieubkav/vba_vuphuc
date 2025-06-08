<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\CourseResource\Pages;
use App\Filament\Admin\Resources\CourseResource\RelationManagers;
use App\Models\Course;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class CourseResource extends Resource
{
    protected static ?string $model = Course::class;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';

    protected static ?string $navigationLabel = 'Khóa học';

    protected static ?string $modelLabel = 'khóa học';

    protected static ?string $pluralModelLabel = 'Khóa học';

    protected static ?string $navigationGroup = 'Quản lý khóa học';

    protected static ?int $navigationSort = 2;

    public static function getNavigationBadge(): ?string
    {
        return (string) static::getModel()::count();
    }

    public static function getNavigationBadgeColor(): string|array|null
    {
        return 'primary';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Tabs::make('Tabs')
                    ->tabs([
                        // Tab 1: Thông tin quan trọng
                        Forms\Components\Tabs\Tab::make('Thông tin quan trọng')
                            ->icon('heroicon-o-information-circle')
                            ->schema([
                                Forms\Components\Section::make('Thông tin cơ bản')
                                    ->schema([
                                        Forms\Components\TextInput::make('title')
                                            ->label('Tiêu đề khóa học')
                                            ->required()
                                            ->maxLength(255)
                                            ->live(onBlur: true)
                                            ->afterStateUpdated(fn (string $context, $state, callable $set) =>
                                                $context === 'create' ? $set('slug', Str::slug($state)) : null
                                            )
                                            ->helperText('Tên khóa học sẽ hiển thị trên website'),

                                        Forms\Components\TextInput::make('slug')
                                            ->label('Đường dẫn (Slug)')
                                            ->maxLength(255)
                                            ->unique(ignoreRecord: true)
                                            ->rules(['alpha_dash'])
                                            ->suffixAction(
                                                Forms\Components\Actions\Action::make('generateSlug')
                                                    ->icon('heroicon-m-arrow-path')
                                                    ->tooltip('Tự động tạo slug từ tiêu đề')
                                                    ->action(function ($get, $set) {
                                                        $title = $get('title');
                                                        if ($title) {
                                                            $set('slug', Str::slug($title));
                                                        }
                                                    })
                                            )
                                            ->helperText('Đường dẫn SEO-friendly. Để trống sẽ tự động tạo từ tiêu đề khi lưu.'),

                                        Forms\Components\Textarea::make('description')
                                            ->label('Mô tả khóa học')
                                            ->rows(4)
                                            ->columnSpanFull()
                                            ->helperText('Mô tả chi tiết về nội dung và mục tiêu của khóa học'),

                                        Forms\Components\FileUpload::make('thumbnail')
                                            ->label('Ảnh đại diện')
                                            ->image()
                                            ->directory('courses/thumbnails')
                                            ->imageEditor()
                                            ->imageEditorAspectRatios([
                                                '16:9',
                                                '4:3',
                                                '1:1',
                                            ])
                                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                                            ->maxSize(5120)
                                            ->saveUploadedFileUsing(function ($file, $get) {
                                                $title = $get('title') ?? 'course';
                                                $customName = 'course-' . $title;

                                                return \App\Actions\ConvertImageToWebp::run(
                                                    $file,
                                                    'courses/thumbnails',
                                                    $customName,
                                                    800,
                                                    450
                                                );
                                            })
                                            ->helperText('Ảnh sẽ được tự động chuyển sang WebP và tối ưu kích thước. Khuyến nghị: 800x450px (16:9)')
                                            ->columnSpanFull(),
                                    ])->columns(2),

                                Forms\Components\Section::make('Cấu hình khóa học')
                                    ->schema([
                                        Forms\Components\Select::make('cat_course_id')
                                            ->label('Danh mục khóa học')
                                            ->relationship('courseCategory', 'name')
                                            ->searchable()
                                            ->preload()
                                            ->live()
                                            ->suffixActions([
                                                Forms\Components\Actions\Action::make('viewCategory')
                                                    ->icon('heroicon-m-eye')
                                                    ->tooltip('Xem thông tin danh mục')
                                                    ->color('info')
                                                    ->action(function ($record, $get) {
                                                        $categoryId = $get('cat_course_id') ?? $record?->cat_course_id;
                                                        if ($categoryId) {
                                                            $category = \App\Models\CatCourse::find($categoryId);
                                                            if ($category) {
                                                                \Filament\Notifications\Notification::make()
                                                                    ->title('Thông tin danh mục')
                                                                    ->body("**{$category->name}**\n\nSlug: {$category->slug}\nMô tả: " . ($category->description ?: 'Chưa có mô tả'))
                                                                    ->info()
                                                                    ->duration(8000)
                                                                    ->send();
                                                            }
                                                        } else {
                                                            \Filament\Notifications\Notification::make()
                                                                ->title('Chưa chọn danh mục')
                                                                ->body('Vui lòng chọn danh mục khóa học trước')
                                                                ->warning()
                                                                ->send();
                                                        }
                                                    })
                                                    ->visible(function ($record, $get) {
                                                        return !empty($get('cat_course_id')) || !empty($record?->cat_course_id);
                                                    }),

                                                Forms\Components\Actions\Action::make('editCategory')
                                                    ->icon('heroicon-m-pencil-square')
                                                    ->tooltip('Chỉnh sửa danh mục')
                                                    ->color('warning')
                                                    ->url(function ($record, $get) {
                                                        $categoryId = $get('cat_course_id') ?? $record?->cat_course_id;
                                                        if ($categoryId) {
                                                            return route('filament.admin.resources.cat-courses.edit', ['record' => $categoryId]);
                                                        }
                                                        return null;
                                                    })
                                                    ->openUrlInNewTab()
                                                    ->visible(function ($record, $get) {
                                                        return !empty($get('cat_course_id')) || !empty($record?->cat_course_id);
                                                    }),
                                            ])
                                            ->helperText('Chọn danh mục phù hợp cho khóa học. Sử dụng các nút bên phải để xem hoặc chỉnh sửa danh mục.'),

                                        Forms\Components\Select::make('level')
                                            ->label('Trình độ')
                                            ->options([
                                                'beginner' => 'Cơ bản',
                                                'intermediate' => 'Trung cấp',
                                                'advanced' => 'Nâng cao',
                                            ])
                                            ->required()
                                            ->default('beginner')
                                            ->helperText('Trình độ phù hợp cho học viên'),

                                        Forms\Components\TextInput::make('duration_hours')
                                            ->label('Thời lượng (giờ)')
                                            ->numeric()
                                            ->default(0)
                                            ->minValue(0)
                                            ->suffix('giờ')
                                            ->helperText('Tổng thời lượng học của khóa học'),

                                        Forms\Components\TextInput::make('max_students')
                                            ->label('Số học viên tối đa')
                                            ->numeric()
                                            ->minValue(1)
                                            ->suffix('người')
                                            ->helperText('Giới hạn số lượng học viên (để trống = không giới hạn)'),

                                        Forms\Components\DatePicker::make('start_date')
                                            ->label('Ngày bắt đầu')
                                            ->helperText('Ngày dự kiến bắt đầu khóa học'),

                                        Forms\Components\DatePicker::make('end_date')
                                            ->label('Ngày kết thúc')
                                            ->helperText('Ngày dự kiến kết thúc khóa học'),
                                    ])->columns(3),

                                Forms\Components\Section::make('Giá cả')
                                    ->schema([
                                        Forms\Components\TextInput::make('price')
                                            ->label('Giá khóa học')
                                            ->numeric()
                                            ->default(0.00)
                                            ->prefix('VND')
                                            ->helperText('Giá chính của khóa học'),

                                        Forms\Components\TextInput::make('compare_price')
                                            ->label('Giá so sánh')
                                            ->numeric()
                                            ->prefix('VND')
                                            ->helperText('Giá gốc để hiển thị khuyến mãi (để trống nếu không có)'),

                                        Forms\Components\Toggle::make('show_price')
                                            ->label('Hiển thị giá')
                                            ->default(true)
                                            ->helperText('Bật/tắt hiển thị giá trên website'),
                                    ])->columns(3),

                                Forms\Components\Section::make('Liên kết & Nhóm học tập')
                                    ->schema([
                                        Forms\Components\TextInput::make('gg_form')
                                            ->label('Link Google Form đăng ký')
                                            ->url()
                                            ->maxLength(255)
                                            ->helperText('Đường dẫn đến form đăng ký khóa học'),

                                        Forms\Components\Toggle::make('show_form_link')
                                            ->label('Hiển thị link đăng ký')
                                            ->default(true)
                                            ->helperText('Bật/tắt hiển thị nút đăng ký'),

                                        Forms\Components\Select::make('course_group_id')
                                            ->label('Nhóm học tập')
                                            ->relationship('courseGroup', 'name')
                                            ->searchable()
                                            ->preload()
                                            ->live()
                                            ->afterStateUpdated(function ($state, callable $set) {
                                                if ($state) {
                                                    $courseGroup = \App\Models\CourseGroup::find($state);
                                                    $set('group_link_display', $courseGroup?->group_link ?? 'Chưa có link nhóm');
                                                } else {
                                                    $set('group_link_display', 'Chưa có nhóm học tập');
                                                }
                                            })
                                            ->helperText('Chọn nhóm học tập cho khóa học'),

                                        Forms\Components\TextInput::make('group_link_display')
                                            ->label('Link nhóm (Zalo/Facebook)')
                                            ->disabled()
                                            ->dehydrated(false)
                                            ->afterStateHydrated(function ($component, $get, $record) {
                                                $courseGroupId = $get('course_group_id') ?? $record?->course_group_id;
                                                if ($courseGroupId) {
                                                    $courseGroup = \App\Models\CourseGroup::find($courseGroupId);
                                                    $component->state($courseGroup?->group_link ?? 'Chưa có link nhóm');
                                                } else {
                                                    $component->state('Chưa có nhóm học tập');
                                                }
                                            })
                                            ->suffixAction(
                                                Forms\Components\Actions\Action::make('openGroupLink')
                                                    ->icon('heroicon-m-arrow-top-right-on-square')
                                                    ->tooltip('Mở link nhóm trong tab mới')
                                                    ->url(function ($record, $get) {
                                                        $courseGroupId = $get('course_group_id');
                                                        if ($courseGroupId) {
                                                            $courseGroup = \App\Models\CourseGroup::find($courseGroupId);
                                                            return $courseGroup?->group_link;
                                                        }
                                                        return $record?->courseGroup?->group_link;
                                                    })
                                                    ->openUrlInNewTab()
                                                    ->visible(function ($record, $get) {
                                                        $courseGroupId = $get('course_group_id');
                                                        if ($courseGroupId) {
                                                            $courseGroup = \App\Models\CourseGroup::find($courseGroupId);
                                                            return !empty($courseGroup?->group_link);
                                                        }
                                                        return !empty($record?->courseGroup?->group_link);
                                                    })
                                            )
                                            ->helperText('Link nhóm được lấy từ nhóm học tập đã chọn'),

                                        Forms\Components\Toggle::make('show_group_link')
                                            ->label('Hiển thị link nhóm')
                                            ->default(true)
                                            ->helperText('Bật/tắt hiển thị nút tham gia nhóm'),

                                        Forms\Components\Select::make('instructor_id')
                                            ->label('Giảng viên')
                                            ->relationship('instructor', 'name')
                                            ->searchable()
                                            ->preload()
                                            ->helperText('Chọn giảng viên phụ trách khóa học'),

                                        Forms\Components\Toggle::make('show_instructor')
                                            ->label('Hiển thị thông tin giảng viên')
                                            ->default(true)
                                            ->helperText('Bật/tắt hiển thị thông tin giảng viên trên trang khóa học'),
                                    ])->columns(3),

                                Forms\Components\Section::make('Nội dung khóa học')
                                    ->schema([
                                        Forms\Components\Textarea::make('requirements')
                                            ->label('Yêu cầu đầu vào')
                                            ->rows(4)
                                            ->helperText('Các kiến thức, kỹ năng cần có trước khi học (mỗi yêu cầu một dòng)')
                                            ->columnSpanFull(),

                                        Forms\Components\Textarea::make('what_you_learn')
                                            ->label('Những gì học được')
                                            ->rows(4)
                                            ->helperText('Các kiến thức, kỹ năng học viên sẽ đạt được (mỗi mục một dòng)')
                                            ->columnSpanFull(),
                                    ]),

                                Forms\Components\Section::make('Trạng thái & Thứ tự')
                                    ->schema([
                                        Forms\Components\Select::make('status')
                                            ->label('Trạng thái')
                                            ->options([
                                                'active' => 'Hoạt động',
                                                'inactive' => 'Tạm dừng',
                                                'draft' => 'Bản nháp',
                                            ])
                                            ->required()
                                            ->default('draft')
                                            ->helperText('Trạng thái hiển thị của khóa học'),

                                        Forms\Components\TextInput::make('order')
                                            ->label('Thứ tự hiển thị')
                                            ->numeric()
                                            ->default(0)
                                            ->minValue(0)
                                            ->helperText('Số thứ tự để sắp xếp khóa học (số nhỏ hiển thị trước)'),

                                        Forms\Components\Toggle::make('is_featured')
                                            ->label('Khóa học nổi bật')
                                            ->default(false)
                                            ->helperText('Đánh dấu là khóa học nổi bật để hiển thị ưu tiên'),
                                    ])->columns(3),
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
                                            ->label('Hình ảnh Social Media (OG Image)')
                                            ->image()
                                            ->directory('courses/og-images')
                                            ->imageEditor()
                                            ->imageEditorAspectRatios([
                                                '16:9',
                                                '1.91:1', // Facebook recommended
                                                '1:1',
                                            ])
                                            ->imageEditorEmptyFillColor('#000000')
                                            ->imageEditorMode(2)
                                            ->imageEditorViewportWidth('1920')
                                            ->imageEditorViewportHeight('1080')
                                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                                            ->maxSize(5120)
                                            ->saveUploadedFileUsing(function ($file, $get) {
                                                $title = $get('title') ?? 'course';
                                                $customName = 'og-' . $title;

                                                return \App\Actions\ConvertImageToWebp::run(
                                                    $file,
                                                    'courses/og-images',
                                                    $customName,
                                                    1200,
                                                    630
                                                );
                                            })
                                            ->helperText('Ảnh sẽ được tự động chuyển sang WebP và tối ưu cho social media. Kích thước: 1200x630px (1.91:1)')
                                            ->columnSpanFull(),
                                    ])->columns(2),
                            ]),
                    ])
                    ->columnSpanFull(),
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
                    ->width(80)
                    ->alignCenter()
                    ->badge()
                    ->color('gray'),

                Tables\Columns\ImageColumn::make('thumbnail')
                    ->label('Ảnh')
                    ->circular()
                    ->size(50),

                Tables\Columns\TextColumn::make('title')
                    ->label('Tên khóa học')
                    ->searchable()
                    ->limit(50)
                    ->tooltip(function (Tables\Columns\TextColumn $column): ?string {
                        $state = $column->getState();
                        if (strlen($state) <= 50) {
                            return null;
                        }
                        return $state;
                    }),

                Tables\Columns\TextColumn::make('courseCategory.name')
                    ->label('Danh mục')
                    ->badge()
                    ->color('info')
                    ->sortable(),

                Tables\Columns\TextColumn::make('instructor.name')
                    ->label('Giảng viên')
                    ->badge()
                    ->color('success')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('price')
                    ->label('Giá')
                    ->money('VND')
                    ->sortable(),

                Tables\Columns\TextColumn::make('level')
                    ->label('Trình độ')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'beginner' => 'Cơ bản',
                        'intermediate' => 'Trung cấp',
                        'advanced' => 'Nâng cao',
                        default => $state,
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'beginner' => 'success',
                        'intermediate' => 'warning',
                        'advanced' => 'danger',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('status')
                    ->label('Trạng thái')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'active' => 'Hoạt động',
                        'inactive' => 'Tạm dừng',
                        'draft' => 'Bản nháp',
                        default => $state,
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'active' => 'success',
                        'inactive' => 'warning',
                        'draft' => 'gray',
                        default => 'gray',
                    }),
                Tables\Columns\IconColumn::make('is_featured')
                    ->label('Nổi bật')
                    ->boolean()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('duration_hours')
                    ->label('Thời lượng (giờ)')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('max_students')
                    ->label('Số học viên tối đa')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('start_date')
                    ->label('Ngày bắt đầu')
                    ->date('d/m/Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('courseGroup.name')
                    ->label('Nhóm học tập')
                    ->badge()
                    ->color('info')
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
                    ->label('Trình độ')
                    ->options([
                        'beginner' => 'Cơ bản',
                        'intermediate' => 'Trung cấp',
                        'advanced' => 'Nâng cao',
                    ]),

                Tables\Filters\SelectFilter::make('status')
                    ->label('Trạng thái')
                    ->options([
                        'active' => 'Hoạt động',
                        'inactive' => 'Tạm dừng',
                        'draft' => 'Bản nháp',
                    ]),

                Tables\Filters\TernaryFilter::make('is_featured')
                    ->label('Nổi bật'),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->label('Sửa'),
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
            //
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
}
