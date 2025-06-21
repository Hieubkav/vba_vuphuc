<?php

namespace App\Filament\Admin\Resources\CatCourseResource\RelationManagers;

use App\Filament\Admin\Resources\CourseResource;
use App\Models\Course;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Support\Enums\FontWeight;
use Filament\Notifications\Notification;

class CoursesRelationManager extends RelationManager
{
    protected static string $relationship = 'courses';

    protected static ?string $title = 'Khóa học trong danh mục';

    protected static ?string $modelLabel = 'khóa học';

    protected static ?string $pluralModelLabel = 'khóa học';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Thông tin cơ bản')
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->label('Tiêu đề khóa học')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('slug')
                            ->label('Đường dẫn (Slug)')
                            ->required()
                            ->maxLength(255)
                            ->unique(Course::class, 'slug', ignoreRecord: true),

                        Forms\Components\Select::make('instructor_id')
                            ->label('Giảng viên')
                            ->relationship('instructor', 'name')
                            ->searchable()
                            ->preload(),
                    ])->columns(2),

                Forms\Components\Section::make('Cấu hình')
                    ->schema([
                        Forms\Components\TextInput::make('duration_hours')
                            ->label('Thời lượng (giờ)')
                            ->required()
                            ->numeric()
                            ->default(0)
                            ->suffix('giờ'),

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

                        Forms\Components\DatePicker::make('start_date')
                            ->label('Ngày bắt đầu'),

                        Forms\Components\TextInput::make('order')
                            ->label('Thứ tự sắp xếp')
                            ->numeric()
                            ->default(0),
                    ])->columns(3),

                Forms\Components\Section::make('Mô tả')
                    ->schema([
                        Forms\Components\RichEditor::make('description')
                            ->label('Mô tả chi tiết')
                            ->required()
                            ->columnSpanFull(),
                    ]),

                Forms\Components\Section::make('Liên kết')
                    ->schema([
                        Forms\Components\TextInput::make('gg_form')
                            ->label('Link Google Form')
                            ->url(),

                        Forms\Components\TextInput::make('group_link')
                            ->label('Link nhóm học tập')
                            ->url(),
                    ])->columns(2)
                    ->collapsible(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->columns([
                Tables\Columns\ImageColumn::make('thumbnail')
                    ->label('Ảnh')
                    ->circular()
                    ->size(40),

                Tables\Columns\TextColumn::make('title')
                    ->label('Tiêu đề khóa học')
                    ->searchable()
                    ->sortable()
                    ->weight(FontWeight::Bold)
                    ->description(fn (Course $record): string =>
                        ($record->instructor ? "GV: {$record->instructor->name}" : '') .
                        ($record->level ? " • " . match($record->level) {
                            'beginner' => 'Cơ bản',
                            'intermediate' => 'Trung cấp', 
                            'advanced' => 'Nâng cao',
                            default => $record->level
                        } : '')
                    ),



                Tables\Columns\TextColumn::make('duration_hours')
                    ->label('Thời lượng')
                    ->suffix(' giờ')
                    ->sortable()
                    ->alignCenter(),

                Tables\Columns\TextColumn::make('enrolled_students_count')
                    ->label('Học viên')
                    ->getStateUsing(fn (Course $record): string =>
                        $record->enrolled_students_count . ($record->max_students ? '/' . $record->max_students : '')
                    )
                    ->badge()
                    ->color('success')
                    ->alignCenter(),

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
                    }),

                Tables\Columns\TextColumn::make('start_date')
                    ->label('Ngày bắt đầu')
                    ->date('d/m/Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('order')
                    ->label('Thứ tự')
                    ->sortable()
                    ->alignCenter()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Trạng thái')
                    ->options([
                        'draft' => 'Nháp',
                        'active' => 'Hoạt động',
                        'inactive' => 'Tạm dừng',
                    ]),

                Tables\Filters\SelectFilter::make('level')
                    ->label('Cấp độ')
                    ->options([
                        'beginner' => 'Cơ bản',
                        'intermediate' => 'Trung cấp',
                        'advanced' => 'Nâng cao',
                    ]),

                Tables\Filters\SelectFilter::make('instructor_id')
                    ->label('Giảng viên')
                    ->relationship('instructor', 'name')
                    ->preload(),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Thêm khóa học mới')
                    ->mutateFormDataUsing(function (array $data): array {
                        $data['cat_course_id'] = $this->ownerRecord->id;
                        return $data;
                    }),
                Tables\Actions\Action::make('add_existing_course')
                    ->label('Thêm khóa học có sẵn')
                    ->icon('heroicon-o-plus-circle')
                    ->color('success')
                    ->modalHeading('Thêm khóa học có sẵn vào danh mục')
                    ->modalDescription(function () {
                        $totalCourses = Course::count();
                        $currentCategoryCourses = Course::where('cat_course_id', $this->ownerRecord->id)->count();
                        $unCategorized = Course::whereNull('cat_course_id')->count();
                        $otherCategories = $totalCourses - $currentCategoryCourses - $unCategorized;

                        return "📊 Tổng cộng: {$totalCourses} khóa học • 🟢 Danh mục này: {$currentCategoryCourses} • ⚪ Chưa phân loại: {$unCategorized} • 🔵 Danh mục khác: {$otherCategories}";
                    })
                    ->modalWidth('2xl')
                    ->form([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\Select::make('filter_type')
                                    ->label('Lọc theo')
                                    ->options(function () {
                                        $totalCourses = Course::count();
                                        $unCategorized = Course::whereNull('cat_course_id')->count();
                                        $otherCategories = Course::whereNotNull('cat_course_id')
                                                                ->where('cat_course_id', '!=', $this->ownerRecord->id)
                                                                ->count();

                                        return [
                                            'all' => "📚 Tất cả khóa học ({$totalCourses})",
                                            'uncategorized' => "⚪ Chỉ khóa học chưa phân loại ({$unCategorized})",
                                            'other_categories' => "🔵 Chỉ khóa học thuộc danh mục khác ({$otherCategories})",
                                        ];
                                    })
                                    ->default('all')
                                    ->live()
                                    ->afterStateUpdated(function (callable $set) {
                                        // Reset selection when filter changes
                                        $set('course_ids', []);
                                    }),

                                Forms\Components\Select::make('status_filter')
                                    ->label('Lọc theo trạng thái')
                                    ->options([
                                        'all' => '🔄 Tất cả trạng thái',
                                        'active' => '✅ Hoạt động',
                                        'inactive' => '⏸️ Tạm dừng',
                                        'draft' => '📝 Nháp',
                                    ])
                                    ->default('all')
                                    ->live()
                                    ->afterStateUpdated(function (callable $set) {
                                        // Reset selection when filter changes
                                        $set('course_ids', []);
                                    }),
                            ]),

                        Forms\Components\Select::make('course_ids')
                            ->label('Chọn khóa học')
                            ->multiple()
                            ->searchable()
                            ->preload()
                            ->options(function (callable $get) {
                                $filterType = $get('filter_type') ?? 'all';
                                $statusFilter = $get('status_filter') ?? 'all';

                                $query = Course::with('courseCategory')->orderBy('title');

                                // Apply status filter
                                if ($statusFilter !== 'all') {
                                    $query->where('status', $statusFilter);
                                }

                                // Apply category filter
                                switch ($filterType) {
                                    case 'uncategorized':
                                        $query->whereNull('cat_course_id');
                                        break;
                                    case 'other_categories':
                                        $query->whereNotNull('cat_course_id')
                                              ->where('cat_course_id', '!=', $this->ownerRecord->id);
                                        break;
                                    // 'all' - no additional filter
                                }

                                return $query->get()->mapWithKeys(function ($course) {
                                    $title = $course->title;
                                    $status = '';
                                    $icon = '';

                                    if ($course->courseCategory) {
                                        if ($course->cat_course_id === $this->ownerRecord->id) {
                                            $icon = '🟢';
                                            $status = ' (Đã có - sẽ bỏ qua)';
                                        } else {
                                            $icon = '🔵';
                                            $status = ' (Từ: ' . $course->courseCategory->name . ')';
                                        }
                                    } else {
                                        $icon = '⚪';
                                        $status = ' (Chưa phân loại)';
                                    }

                                    return [$course->id => $icon . ' ' . $title . $status];
                                });
                            })
                            ->required()
                            ->helperText('🟢 Đã có trong danh mục này | 🔵 Thuộc danh mục khác | ⚪ Chưa phân loại')
                            ->placeholder('Tìm kiếm và chọn khóa học...')
                            ->columnSpanFull()
                    ])
                    ->action(function (array $data) {
                        $courseIds = $data['course_ids'] ?? [];
                        $addedCount = 0;
                        $skippedCount = 0;
                        $addedCourses = [];

                        foreach ($courseIds as $courseId) {
                            $course = Course::find($courseId);
                            if ($course) {
                                if ($course->cat_course_id !== $this->ownerRecord->id) {
                                    $course->update(['cat_course_id' => $this->ownerRecord->id]);
                                    $addedCourses[] = $course->title;
                                    $addedCount++;
                                } else {
                                    $skippedCount++;
                                }
                            }
                        }

                        if ($addedCount > 0) {
                            $message = "✅ Đã thêm thành công {$addedCount} khóa học vào danh mục:\n\n";
                            foreach ($addedCourses as $courseName) {
                                $message .= "• " . $courseName . "\n";
                            }

                            if ($skippedCount > 0) {
                                $message .= "\n🟡 Đã bỏ qua {$skippedCount} khóa học (đã có trong danh mục này)";
                            }

                            Notification::make()
                                ->title('🎉 Thành công!')
                                ->body($message)
                                ->success()
                                ->duration(6000)
                                ->send();
                        } else {
                            Notification::make()
                                ->title('ℹ️ Thông báo')
                                ->body('Không có khóa học nào được thêm.\nTất cả khóa học đã chọn đều có sẵn trong danh mục này.')
                                ->warning()
                                ->send();
                        }
                    }),
                Tables\Actions\Action::make('view_all_courses')
                    ->label('Xem tất cả khóa học')
                    ->icon('heroicon-o-academic-cap')
                    ->color('info')
                    ->url(fn () => CourseResource::getUrl('index', [
                        'tableFilters[cat_course_id][value]' => $this->ownerRecord->id
                    ])),
            ])
            ->actions([
                Tables\Actions\Action::make('view_frontend')
                    ->label('Xem trên website')
                    ->icon('heroicon-o-eye')
                    ->color('info')
                    ->url(fn ($record) => route('courses.show', $record->slug))
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
            ->defaultSort('order', 'asc')
            ->reorderable('order')
            ->emptyStateHeading('Chưa có khóa học nào')
            ->emptyStateDescription('Thêm khóa học đầu tiên cho danh mục này.')
            ->emptyStateIcon('heroicon-o-academic-cap');
    }
}
