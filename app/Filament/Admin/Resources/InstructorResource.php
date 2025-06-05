<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\InstructorResource\Pages;
use App\Filament\Admin\Resources\CourseResource;
use App\Models\Instructor;
use App\Traits\HasImageUpload;
use App\Traits\SimpleFilamentOptimization;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class InstructorResource extends Resource
{
    use HasImageUpload, SimpleFilamentOptimization;

    protected static ?string $model = Instructor::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $navigationLabel = 'Giảng viên';

    protected static ?string $modelLabel = 'giảng viên';

    protected static ?string $pluralModelLabel = 'giảng viên';

    protected static ?string $navigationGroup = 'Quản lý khóa học';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Thông tin cơ bản')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Họ và tên')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (string $context, $state, Forms\Set $set) =>
                                $context === 'create' ? $set('slug', \Illuminate\Support\Str::slug($state)) : null
                            ),

                        Forms\Components\TextInput::make('slug')
                            ->label('Đường dẫn (Slug)')
                            ->required()
                            ->maxLength(255)
                            ->unique(Instructor::class, 'slug', ignoreRecord: true)
                            ->rules(['alpha_dash'])
                            ->helperText('Đường dẫn URL cho trang giảng viên'),

                        Forms\Components\TextInput::make('email')
                            ->label('Email')
                            ->email()
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),

                        Forms\Components\TextInput::make('phone')
                            ->label('Số điện thoại')
                            ->tel()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('website')
                            ->label('Website')
                            ->url()
                            ->maxLength(255)
                            ->helperText('Website cá nhân của giảng viên'),

                        self::createLogoUpload(
                            'avatar',
                            'Ảnh đại diện',
                            'instructors/avatars',
                            400
                        ),
                    ])->columns(2),

                Forms\Components\Section::make('Thông tin chuyên môn')
                    ->schema([
                        Forms\Components\TextInput::make('specialization')
                            ->label('Chuyên môn')
                            ->maxLength(255)
                            ->helperText('Ví dụ: Excel, VBA, Kế toán, Quản lý'),

                        Forms\Components\TextInput::make('experience_years')
                            ->label('Số năm kinh nghiệm')
                            ->numeric()
                            ->default(0)
                            ->minValue(0)
                            ->maxValue(50),

                        Forms\Components\Textarea::make('education')
                            ->label('Học vấn')
                            ->rows(3)
                            ->helperText('Trình độ học vấn, bằng cấp'),

                        Forms\Components\Repeater::make('certifications')
                            ->label('Chứng chỉ')
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->label('Tên chứng chỉ')
                                    ->required(),
                                Forms\Components\TextInput::make('issuer')
                                    ->label('Đơn vị cấp'),
                                Forms\Components\DatePicker::make('date')
                                    ->label('Ngày cấp'),
                            ])
                            ->defaultItems(0)
                            ->addActionLabel('Thêm chứng chỉ')
                            ->collapsible(),
                    ])->columns(2),

                Forms\Components\Section::make('Thông tin bổ sung')
                    ->schema([
                        Forms\Components\RichEditor::make('bio')
                            ->label('Tiểu sử')
                            ->columnSpanFull()
                            ->toolbarButtons([
                                'bold', 'italic', 'underline',
                                'bulletList', 'orderedList',
                                'h2', 'h3', 'link'
                            ]),

                        Forms\Components\Textarea::make('achievements')
                            ->label('Thành tích')
                            ->rows(3)
                            ->helperText('Các thành tích, giải thưởng đáng chú ý'),

                        Forms\Components\Textarea::make('teaching_philosophy')
                            ->label('Triết lý giảng dạy')
                            ->rows(3)
                            ->helperText('Phương pháp và triết lý giảng dạy'),

                        Forms\Components\TextInput::make('hourly_rate')
                            ->label('Giá theo giờ (VNĐ)')
                            ->numeric()
                            ->prefix('VNĐ')
                            ->helperText('Mức phí giảng dạy theo giờ'),
                    ])->columns(2),

                Forms\Components\Section::make('Liên kết mạng xã hội')
                    ->schema([
                        Forms\Components\Repeater::make('social_links')
                            ->label('Liên kết mạng xã hội')
                            ->schema([
                                Forms\Components\Select::make('platform')
                                    ->label('Nền tảng')
                                    ->options([
                                        'facebook' => 'Facebook',
                                        'linkedin' => 'LinkedIn',
                                        'youtube' => 'YouTube',
                                        'website' => 'Website',
                                        'other' => 'Khác',
                                    ])
                                    ->required(),
                                Forms\Components\TextInput::make('url')
                                    ->label('Đường dẫn')
                                    ->url()
                                    ->required(),
                            ])
                            ->defaultItems(0)
                            ->addActionLabel('Thêm liên kết')
                            ->collapsible()
                            ->columnSpanFull(),
                    ]),

                Forms\Components\Section::make('Cấu hình hiển thị')
                    ->schema([
                        Forms\Components\Select::make('status')
                            ->label('Trạng thái')
                            ->options([
                                'active' => 'Hoạt động',
                                'inactive' => 'Tạm dừng',
                            ])
                            ->default('active')
                            ->required(),

                        Forms\Components\TextInput::make('order')
                            ->label('Thứ tự hiển thị')
                            ->numeric()
                            ->default(0)
                            ->helperText('Số nhỏ hơn sẽ hiển thị trước'),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('avatar')
                    ->label('Ảnh')
                    ->circular()
                    ->size(50),

                Tables\Columns\TextColumn::make('name')
                    ->label('Họ và tên')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->description(fn ($record): string =>
                        ($record->specialization ? "Chuyên môn: {$record->specialization}" : '') .
                        ($record->experience_years > 0 ? " • {$record->experience_years} năm kinh nghiệm" : '')
                    ),

                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->copyable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('phone')
                    ->label('Điện thoại')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('courses_count')
                    ->label('Khóa học')
                    ->counts('courses')
                    ->badge()
                    ->color('success')
                    ->alignCenter()
                    ->width(100),

                Tables\Columns\TextColumn::make('experience_years')
                    ->label('Kinh nghiệm')
                    ->suffix(' năm')
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
                        'active' => 'Hoạt động',
                        'inactive' => 'Tạm dừng',
                    ])
                    ->selectablePlaceholder(false)
                    ->width(120),

                // Cột ẩn mặc định
                Tables\Columns\TextColumn::make('specialization')
                    ->label('Chuyên môn')
                    ->searchable()
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
                        'inactive' => 'Tạm dừng',
                    ]),

                Tables\Filters\SelectFilter::make('specialization')
                    ->label('Chuyên môn')
                    ->options(function () {
                        return Instructor::whereNotNull('specialization')
                            ->distinct()
                            ->pluck('specialization', 'specialization')
                            ->toArray();
                    }),
            ])
            ->actions([
                Tables\Actions\Action::make('manage_courses')
                    ->label('Quản lý khóa học')
                    ->icon('heroicon-o-academic-cap')
                    ->color('success')
                    ->url(fn ($record) => CourseResource::getUrl('index', ['tableFilters[instructor_id][value]' => $record->id]))
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
            ->reorderable('order')
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
            'index' => Pages\ListInstructors::route('/'),
            'create' => Pages\CreateInstructor::route('/create'),
            'edit' => Pages\EditInstructor::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return (string) static::getModel()::where('status', 'active')->count();
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
        return array (
  0 => 'id',
  1 => 'name',
  2 => 'email',
  3 => 'specialization',
  4 => 'bio',
  5 => 'status',
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
                $query->select(['id,title,instructor_id']);
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
  1 => 'email',
  2 => 'specialization',
);
    }
}