<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\CourseMaterialResource\Pages;
use App\Models\CourseMaterial;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;


class CourseMaterialResource extends Resource
{

    protected static ?string $model = CourseMaterial::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'Tài liệu khóa học';

    protected static ?string $modelLabel = 'tài liệu khóa học';

    protected static ?string $pluralModelLabel = 'tài liệu khóa học';

    protected static ?string $navigationGroup = 'Quản lý khóa học';

    protected static ?int $navigationSort = 6;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Thông tin cơ bản')
                    ->schema([
                        Forms\Components\Select::make('course_id')
                            ->label('Khóa học')
                            ->relationship('course', 'title')
                            ->searchable()
                            ->preload()
                            ->required(),

                        Forms\Components\TextInput::make('title')
                            ->label('Tiêu đề tài liệu')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\Textarea::make('description')
                            ->label('Mô tả')
                            ->rows(3)
                            ->columnSpanFull(),
                    ])->columns(2),

                Forms\Components\Section::make('File tài liệu')
                    ->schema([
                        Forms\Components\FileUpload::make('file_path')
                            ->label('File tài liệu')
                            ->directory('course-materials')
                            ->acceptedFileTypes(['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'])
                            ->maxSize(50000) // 50MB
                            ->downloadable()
                            ->required(),

                        Forms\Components\Select::make('material_type')
                            ->label('Loại tài liệu')
                            ->options([
                                'document' => 'Tài liệu',
                                'video' => 'Video',
                                'audio' => 'Audio',
                                'presentation' => 'Bài thuyết trình',
                                'exercise' => 'Bài tập',
                                'other' => 'Khác',
                            ])
                            ->required(),

                        Forms\Components\Select::make('access_type')
                            ->label('Quyền truy cập')
                            ->options([
                                'public' => 'Công khai',
                                'enrolled' => 'Dành cho học viên',
                                'premium' => 'Premium',
                            ])
                            ->default('enrolled')
                            ->required(),
                    ])->columns(2),

                Forms\Components\Section::make('Cài đặt')
                    ->schema([
                        Forms\Components\Toggle::make('is_downloadable')
                            ->label('Cho phép tải xuống')
                            ->default(true),

                        Forms\Components\Toggle::make('is_preview')
                            ->label('Cho phép xem trước')
                            ->default(false),

                        Forms\Components\TextInput::make('order')
                            ->label('Thứ tự hiển thị')
                            ->numeric()
                            ->default(0)
                            ->required(),

                        Forms\Components\Select::make('status')
                            ->label('Trạng thái')
                            ->options([
                                'active' => 'Hoạt động',
                                'inactive' => 'Không hoạt động',
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
                Tables\Columns\TextColumn::make('course.title')
                    ->label('Khóa học')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color('info'),

                Tables\Columns\TextColumn::make('title')
                    ->label('Tiêu đề')
                    ->searchable()
                    ->sortable()
                    ->limit(30),

                Tables\Columns\TextColumn::make('material_type')
                    ->label('Loại')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'document' => 'success',
                        'video' => 'warning',
                        'audio' => 'info',
                        'presentation' => 'danger',
                        'exercise' => 'primary',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'document' => 'Tài liệu',
                        'video' => 'Video',
                        'audio' => 'Audio',
                        'presentation' => 'Bài thuyết trình',
                        'exercise' => 'Bài tập',
                        'other' => 'Khác',
                        default => $state,
                    }),

                Tables\Columns\TextColumn::make('access_type')
                    ->label('Quyền truy cập')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'public' => 'success',
                        'enrolled' => 'warning',
                        'premium' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'public' => 'Công khai',
                        'enrolled' => 'Học viên',
                        'premium' => 'Premium',
                        default => $state,
                    }),

                Tables\Columns\IconColumn::make('is_downloadable')
                    ->label('Tải xuống')
                    ->boolean()
                    ->trueIcon('heroicon-o-arrow-down-tray')
                    ->falseIcon('heroicon-o-x-mark')
                    ->trueColor('success')
                    ->falseColor('danger'),

                Tables\Columns\IconColumn::make('is_preview')
                    ->label('Xem trước')
                    ->boolean()
                    ->trueIcon('heroicon-o-eye')
                    ->falseIcon('heroicon-o-x-mark')
                    ->trueColor('info')
                    ->falseColor('gray'),

                Tables\Columns\TextColumn::make('order')
                    ->label('Thứ tự')
                    ->numeric()
                    ->sortable()
                    ->alignCenter(),

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
                Tables\Filters\SelectFilter::make('course_id')
                    ->label('Khóa học')
                    ->relationship('course', 'title')
                    ->preload(),

                Tables\Filters\SelectFilter::make('material_type')
                    ->label('Loại tài liệu')
                    ->options([
                        'document' => 'Tài liệu',
                        'video' => 'Video',
                        'audio' => 'Audio',
                        'presentation' => 'Bài thuyết trình',
                        'exercise' => 'Bài tập',
                        'other' => 'Khác',
                    ]),

                Tables\Filters\SelectFilter::make('access_type')
                    ->label('Quyền truy cập')
                    ->options([
                        'public' => 'Công khai',
                        'enrolled' => 'Học viên',
                        'premium' => 'Premium',
                    ]),

                Tables\Filters\SelectFilter::make('status')
                    ->label('Trạng thái')
                    ->options([
                        'active' => 'Hoạt động',
                        'inactive' => 'Không hoạt động',
                    ]),
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
            'index' => Pages\ListCourseMaterials::route('/'),
            'create' => Pages\CreateCourseMaterial::route('/create'),
            'edit' => Pages\EditCourseMaterial::route('/{record}/edit'),
        ];
    }

    /**
     * Lấy danh sách cột cần thiết cho table
     */
    protected static function getTableColumns(): array
    {
        return ['id', 'course_id', 'title', 'file_path', 'file_type', 'access_type', 'order', 'created_at'];
    }

    /**
     * Lấy relationships cần thiết cho form
     */
    protected static function getFormRelationships(): array
    {
        return [
            'course' => function($query) {
                $query->select(['id', 'title']);
            }
        ];
    }

    /**
     * Lấy các cột có thể search
     */
    protected static function getSearchableColumns(): array
    {
        return ['title'];
    }
}
