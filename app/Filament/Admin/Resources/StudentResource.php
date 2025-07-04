<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\StudentResource\Pages;
use App\Models\Student;
use App\Traits\HasImageUpload;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\Section;
use Filament\Support\Enums\FontWeight;

class StudentResource extends Resource
{
    use HasImageUpload;

    protected static ?string $model = Student::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationLabel = 'Học viên';

    protected static ?string $modelLabel = 'học viên';

    protected static ?string $pluralModelLabel = 'học viên';

    protected static ?string $navigationGroup = 'Quản lý khóa học';

    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Thông tin cơ bản - hàng đầu
                Section::make('Thông tin cơ bản')
                    ->description('Thông tin cá nhân và liên hệ của học viên')
                    ->icon('heroicon-o-user')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Họ và tên')
                            ->required()
                            ->maxLength(255)
                            ->columnSpan(2),

                        Forms\Components\TextInput::make('email')
                            ->label('Email')
                            ->email()
                            ->required()
                            ->unique(Student::class, 'email', ignoreRecord: true)
                            ->maxLength(255),

                        Forms\Components\TextInput::make('phone')
                            ->label('Số điện thoại')
                            ->tel()
                            ->maxLength(255),

                        Forms\Components\DatePicker::make('birth_date')
                            ->label('Ngày sinh')
                            ->maxDate(now()->subYears(16))
                            ->displayFormat('d/m/Y'),

                        Forms\Components\Select::make('gender')
                            ->label('Giới tính')
                            ->options([
                                'male' => 'Nam',
                                'female' => 'Nữ',
                                'other' => 'Khác',
                            ]),
                    ])
                    ->columns(3)
                    ->collapsible(),

                // Thông tin bổ sung và địa chỉ
                Section::make('Thông tin bổ sung')
                    ->description('Nghề nghiệp, học vấn và địa chỉ')
                    ->icon('heroicon-o-academic-cap')
                    ->schema([
                        Forms\Components\TextInput::make('occupation')
                            ->label('Nghề nghiệp')
                            ->maxLength(255),

                        Forms\Components\Select::make('education_level')
                            ->label('Trình độ học vấn')
                            ->options([
                                'high_school' => 'Trung học phổ thông',
                                'college' => 'Cao đẳng',
                                'university' => 'Đại học',
                                'master' => 'Thạc sĩ',
                                'phd' => 'Tiến sĩ',
                                'other' => 'Khác',
                            ]),

                        Forms\Components\Textarea::make('address')
                            ->label('Địa chỉ')
                            ->rows(3)
                            ->columnSpanFull(),
                    ])
                    ->columns(2)
                    ->collapsible(),

                // Mục tiêu học tập
                Section::make('Mục tiêu học tập')
                    ->description('Mục tiêu và sở thích học tập của học viên')
                    ->icon('heroicon-o-light-bulb')
                    ->schema([
                        Forms\Components\Textarea::make('learning_goals')
                            ->label('Mục tiêu học tập')
                            ->rows(4)
                            ->helperText('Mô tả mục tiêu và kỳ vọng của học viên')
                            ->columnSpanFull(),

                        Forms\Components\TagsInput::make('interests')
                            ->label('Sở thích/Lĩnh vực quan tâm')
                            ->helperText('Nhập các từ khóa và nhấn Enter')
                            ->columnSpanFull(),
                    ])
                    ->collapsible(),

                // Hình đại diện và cài đặt tài khoản
                Section::make('Cài đặt tài khoản')
                    ->description('Hình đại diện và trạng thái tài khoản')
                    ->icon('heroicon-o-cog-6-tooth')
                    ->schema([
                        Forms\Components\FileUpload::make('avatar')
                            ->label('Ảnh đại diện')
                            ->image()
                            ->directory('students/avatars')
                            ->visibility('public')
                            ->maxSize(2048) // 2MB
                            ->imageEditor()
                            ->imageEditorAspectRatios(['1:1'])
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                            ->helperText('Khuyến nghị kích thước: 300x300px (hình vuông)')
                            ->columnSpan(1)
                            ->saveUploadedFileUsing(function ($file, $get) {
                                $name = $get('name') ?? 'student';
                                $customName = 'student-' . $name;

                                return \App\Actions\ConvertImageToWebp::run(
                                    $file,
                                    'students/avatars',
                                    $customName,
                                    300,
                                    300
                                );
                            }),

                        Forms\Components\Group::make([
                            Forms\Components\Select::make('status')
                                ->label('Trạng thái')
                                ->required()
                                ->options([
                                    'active' => 'Hoạt động',
                                    'inactive' => 'Tạm dừng',
                                    'suspended' => 'Bị khóa',
                                ])
                                ->default('active'),

                            Forms\Components\DateTimePicker::make('email_verified_at')
                                ->label('Email đã xác thực')
                                ->helperText('Để trống nếu email chưa được xác thực'),

                            Forms\Components\TextInput::make('order')
                                ->label('Thứ tự sắp xếp')
                                ->numeric()
                                ->default(0)
                                ->helperText('Số nhỏ hơn sẽ hiển thị trước'),
                        ])
                        ->columnSpan(2)
                        ->columns(1),
                    ])
                    ->columns(3)
                    ->collapsible(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('avatar')
                    ->label('Ảnh đại diện')
                    ->circular()
                    ->size(50)
                    ->defaultImageUrl(asset('images/default-avatar.png')),

                Tables\Columns\TextColumn::make('name')
                    ->label('Họ và tên')
                    ->searchable()
                    ->sortable()
                    ->weight(FontWeight::Bold)
                    ->description(fn (Student $record): string =>
                        $record->email
                    ),

                Tables\Columns\TextColumn::make('phone')
                    ->label('Số điện thoại')
                    ->searchable()
                    ->copyable()
                    ->copyMessage('Đã sao chép số điện thoại'),

                Tables\Columns\TextColumn::make('age')
                    ->label('Tuổi')
                    ->getStateUsing(fn (Student $record): ?string =>
                        $record->age ? $record->age . ' tuổi' : null
                    )
                    ->sortable(query: function (Builder $query, string $direction): Builder {
                        return $query->orderBy('birth_date', $direction === 'asc' ? 'desc' : 'asc');
                    }),

                Tables\Columns\TextColumn::make('gender_display')
                    ->label('Giới tính')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Nam' => 'blue',
                        'Nữ' => 'pink',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('education_level_display')
                    ->label('Trình độ')
                    ->badge()
                    ->color('info'),

                Tables\Columns\TextColumn::make('enrolled_courses_count')
                    ->label('Khóa học')
                    ->getStateUsing(fn (Student $record): string =>
                        $record->getEnrolledCoursesCount() . ' khóa học'
                    )
                    ->badge()
                    ->color('success'),

                Tables\Columns\TextColumn::make('average_progress')
                    ->label('Tiến độ TB')
                    ->getStateUsing(fn (Student $record): string =>
                        $record->getAverageProgress() . '%'
                    )
                    ->badge()
                    ->color(function (Student $record): string {
                        $progress = $record->getAverageProgress();
                        if ($progress >= 80) return 'success';
                        if ($progress >= 50) return 'warning';
                        return 'danger';
                    }),

                Tables\Columns\IconColumn::make('email_verified_at')
                    ->label('Email xác thực')
                    ->boolean()
                    ->getStateUsing(fn (Student $record): bool =>
                        !is_null($record->email_verified_at)
                    )
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),

                Tables\Columns\TextColumn::make('status')
                    ->label('Trạng thái')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'active' => 'success',
                        'inactive' => 'warning',
                        'suspended' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'active' => 'Hoạt động',
                        'inactive' => 'Tạm dừng',
                        'suspended' => 'Bị khóa',
                        default => $state,
                    }),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Ngày đăng ký')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('gender')
                    ->label('Giới tính')
                    ->options([
                        'male' => 'Nam',
                        'female' => 'Nữ',
                        'other' => 'Khác',
                    ]),

                Tables\Filters\SelectFilter::make('education_level')
                    ->label('Trình độ học vấn')
                    ->options([
                        'high_school' => 'Trung học phổ thông',
                        'college' => 'Cao đẳng',
                        'university' => 'Đại học',
                        'master' => 'Thạc sĩ',
                        'phd' => 'Tiến sĩ',
                        'other' => 'Khác',
                    ]),

                Tables\Filters\SelectFilter::make('status')
                    ->label('Trạng thái')
                    ->options([
                        'active' => 'Hoạt động',
                        'inactive' => 'Tạm dừng',
                        'suspended' => 'Bị khóa',
                    ]),

                Tables\Filters\TernaryFilter::make('email_verified_at')
                    ->label('Email đã xác thực')
                    ->nullable(),
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
            ->reorderable('order')
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            // RelationManagers\CoursesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListStudents::route('/'),
            'create' => Pages\CreateStudent::route('/create'),
            'edit' => Pages\EditStudent::route('/{record}/edit'),
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
  2 => 'email',
  3 => 'phone',
  4 => 'status',
  5 => 'created_at',
);
    }

    /**
     * Lấy relationships cần thiết cho form
     */
    protected static function getFormRelationships(): array
    {
        return [
            'courses' => function($query) {
                $query->select(['id,title']);
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
  2 => 'phone',
);
    }
}