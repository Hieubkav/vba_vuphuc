<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\CourseGroupResource\Pages;
use App\Filament\Admin\Resources\CourseGroupResource\RelationManagers;
use App\Models\CourseGroup;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Traits\OptimizedFilamentResource;
use Illuminate\Support\Str;

class CourseGroupResource extends Resource
{
    use OptimizedFilamentResource;

    protected static ?string $model = CourseGroup::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $modelLabel = 'nhóm khóa học';

    protected static ?string $pluralModelLabel = 'nhóm khóa học';

    protected static ?string $navigationLabel = 'Nhóm khóa học';

    protected static ?string $navigationGroup = 'Quản lý khóa học';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Thông tin cơ bản')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Tên nhóm')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (string $context, $state, callable $set) => $context === 'create' ? $set('slug', Str::slug($state)) : null),

                        Forms\Components\TextInput::make('slug')
                            ->label('Slug')
                            ->required()
                            ->maxLength(255)
                            ->unique(CourseGroup::class, 'slug', ignoreRecord: true),

                        Forms\Components\Textarea::make('description')
                            ->label('Mô tả nhóm')
                            ->rows(3)
                            ->columnSpanFull(),
                    ])->columns(2),

                Forms\Components\Section::make('Cấu hình nhóm')
                    ->schema([
                        Forms\Components\TextInput::make('group_link')
                            ->label('Link nhóm')
                            ->url()
                            ->placeholder('https://www.facebook.com/groups/...')
                            ->maxLength(255),

                        Forms\Components\Select::make('group_type')
                            ->label('Loại nhóm')
                            ->required()
                            ->options([
                                'facebook' => 'Facebook',
                                'zalo' => 'Zalo',
                                'telegram' => 'Telegram',
                            ])
                            ->default('facebook'),

                        Forms\Components\Select::make('level')
                            ->label('Cấp độ')
                            ->required()
                            ->options([
                                'beginner' => 'Cơ bản',
                                'intermediate' => 'Trung cấp',
                                'advanced' => 'Nâng cao',
                            ])
                            ->default('beginner'),

                        Forms\Components\Select::make('color')
                            ->label('Màu chủ đạo')
                            ->options([
                                '#dc2626' => '🔴 Đỏ',
                                '#2563eb' => '🔵 Xanh dương',
                                '#16a34a' => '🟢 Xanh lá',
                                '#ca8a04' => '🟡 Vàng',
                                '#9333ea' => '🟣 Tím',
                                '#ea580c' => '🟠 Cam',
                                '#0891b2' => '🔷 Xanh cyan',
                                '#be185d' => '🩷 Hồng',
                            ])
                            ->default('#dc2626')
                            ->required(),
                    ])->columns(2),

                Forms\Components\Section::make('Thành viên')
                    ->schema([
                        Forms\Components\TextInput::make('max_members')
                            ->label('Số thành viên tối đa')
                            ->numeric()
                            ->placeholder('Để trống nếu không giới hạn'),

                        Forms\Components\TextInput::make('current_members')
                            ->label('Số thành viên hiện tại')
                            ->required()
                            ->numeric()
                            ->default(0)
                            ->minValue(0),
                    ])->columns(2),

                Forms\Components\Section::make('Giảng viên')
                    ->schema([
                        Forms\Components\TextInput::make('instructor_name')
                            ->label('Tên giảng viên')
                            ->maxLength(255),

                        Forms\Components\Textarea::make('instructor_bio')
                            ->label('Tiểu sử giảng viên')
                            ->rows(3)
                            ->columnSpanFull(),
                    ])->columns(1),

                Forms\Components\Section::make('Cài đặt')
                    ->schema([
                        Forms\Components\FileUpload::make('thumbnail')
                            ->label('Hình đại diện')
                            ->image()
                            ->directory('course-groups')
                            ->visibility('public'),

                        Forms\Components\Select::make('icon')
                            ->label('Icon nhóm')
                            ->options([
                                'fas fa-users' => '👥 Nhóm người',
                                'fas fa-comments' => '💬 Chat',
                                'fas fa-graduation-cap' => '🎓 Học tập',
                                'fas fa-book' => '📚 Sách',
                                'fas fa-laptop-code' => '💻 Lập trình',
                                'fas fa-chart-bar' => '📊 Biểu đồ',
                                'fas fa-calculator' => '🧮 Máy tính',
                                'fas fa-file-excel' => '📗 Excel',
                                'fas fa-database' => '🗄️ Dữ liệu',
                                'fas fa-lightbulb' => '💡 Ý tưởng',
                                'fas fa-rocket' => '🚀 Khởi nghiệp',
                                'fas fa-handshake' => '🤝 Hợp tác',
                            ])
                            ->default('fas fa-users')
                            ->required(),

                        Forms\Components\Toggle::make('is_featured')
                            ->label('Nổi bật')
                            ->default(false),

                        Forms\Components\TextInput::make('order')
                            ->label('Thứ tự hiển thị')
                            ->required()
                            ->numeric()
                            ->default(0),

                        Forms\Components\Select::make('status')
                            ->label('Trạng thái')
                            ->required()
                            ->options([
                                'active' => 'Hoạt động',
                                'inactive' => 'Không hoạt động',
                            ])
                            ->default('active'),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('thumbnail')
                    ->label('Hình ảnh')
                    ->circular()
                    ->size(40),

                Tables\Columns\TextColumn::make('name')
                    ->label('Tên nhóm')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('group_type')
                    ->label('Loại nhóm')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'facebook' => 'info',
                        'zalo' => 'warning',
                        'telegram' => 'success',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'facebook' => 'Facebook',
                        'zalo' => 'Zalo',
                        'telegram' => 'Telegram',
                        default => $state,
                    }),

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
                    }),

                Tables\Columns\TextColumn::make('members')
                    ->label('Thành viên')
                    ->getStateUsing(function ($record) {
                        if ($record->max_members) {
                            return "{$record->current_members}/{$record->max_members}";
                        }
                        return $record->current_members;
                    })
                    ->sortable(['current_members']),

                Tables\Columns\TextColumn::make('instructor_name')
                    ->label('Giảng viên')
                    ->searchable()
                    ->toggleable(),

                Tables\Columns\IconColumn::make('is_featured')
                    ->label('Nổi bật')
                    ->boolean()
                    ->trueIcon('heroicon-o-star')
                    ->falseIcon('heroicon-o-star')
                    ->trueColor('warning')
                    ->falseColor('gray'),

                Tables\Columns\TextColumn::make('order')
                    ->label('Thứ tự')
                    ->numeric()
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

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Ngày tạo')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('group_type')
                    ->label('Loại nhóm')
                    ->options([
                        'facebook' => 'Facebook',
                        'zalo' => 'Zalo',
                        'telegram' => 'Telegram',
                    ]),

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
                        'active' => 'Hoạt động',
                        'inactive' => 'Không hoạt động',
                    ]),
            ])
            ->actions([
                Tables\Actions\Action::make('view_frontend')
                    ->label('Xem trên website')
                    ->icon('heroicon-o-globe-alt')
                    ->color('info')
                    ->url(fn () => route('course-groups.index'))
                    ->openUrlInNewTab(),
                Tables\Actions\EditAction::make()
                    ->label('Sửa'),
                Tables\Actions\Action::make('view_group')
                    ->label('Xem nhóm chat')
                    ->icon('heroicon-o-chat-bubble-left-right')
                    ->color('success')
                    ->url(fn ($record) => $record->group_link)
                    ->openUrlInNewTab()
                    ->visible(fn ($record) => !empty($record->group_link)),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->label('Xóa đã chọn'),
                ]),
            ])
            ->defaultSort('order');
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
            'index' => Pages\ListCourseGroups::route('/'),
            'create' => Pages\CreateCourseGroup::route('/create'),
            'edit' => Pages\EditCourseGroup::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        $optimizationService = app(\App\Services\FilamentOptimizationService::class);
        
        return $optimizationService->cacheQuery(
            'CourseGroupResource_count_badge',
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
        return array (
  0 => 'id',
  1 => 'name',
  2 => 'description',
  3 => 'group_link',
  4 => 'group_type',
  5 => 'order',
  6 => 'status',
  7 => 'created_at',
);
    }

    /**
     * Lấy relationships cần thiết cho form
     */
    protected static function getFormRelationships(): array
    {
        return [];
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