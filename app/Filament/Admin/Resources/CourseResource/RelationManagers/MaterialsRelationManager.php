<?php

namespace App\Filament\Admin\Resources\CourseResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Storage;

class MaterialsRelationManager extends RelationManager
{
    protected static string $relationship = 'materials';

    protected static ?string $title = 'Tài liệu khóa học';

    protected static ?string $modelLabel = 'tài liệu';

    protected static ?string $pluralModelLabel = 'tài liệu';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Thông tin tài liệu')
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->label('Tiêu đề tài liệu')
                            ->required()
                            ->maxLength(255)
                            ->helperText('Tên hiển thị của tài liệu'),

                        Forms\Components\Textarea::make('description')
                            ->label('Mô tả')
                            ->rows(3)
                            ->maxLength(500)
                            ->helperText('Mô tả chi tiết về tài liệu (tùy chọn)'),

                        Forms\Components\FileUpload::make('file_path')
                            ->label('Tệp tài liệu')
                            ->directory('course-materials')
                            ->acceptedFileTypes(['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/vnd.ms-powerpoint', 'application/vnd.openxmlformats-officedocument.presentationml.presentation', 'video/*', 'audio/*', 'image/*'])
                            ->maxSize(50 * 1024) // 50MB
                            ->required()
                            ->helperText('Hỗ trợ: PDF, Word, PowerPoint, Video, Audio, Hình ảnh (tối đa 50MB)')
                            ->reactive()
                            ->afterStateUpdated(function ($state, callable $set) {
                                if ($state) {
                                    // Tự động điền tên file
                                    $fileName = pathinfo($state->getClientOriginalName(), PATHINFO_FILENAME);
                                    $set('file_name', $fileName);
                                    
                                    // Tự động điền loại file
                                    $extension = strtolower(pathinfo($state->getClientOriginalName(), PATHINFO_EXTENSION));
                                    $fileType = $this->getFileTypeFromExtension($extension);
                                    $set('file_type', $extension);
                                    $set('material_type', $fileType);
                                }
                            }),

                        Forms\Components\TextInput::make('file_name')
                            ->label('Tên file')
                            ->maxLength(255)
                            ->helperText('Tên file gốc (tự động điền)'),

                        Forms\Components\TextInput::make('file_type')
                            ->label('Loại file')
                            ->maxLength(50)
                            ->helperText('Phần mở rộng file (tự động điền)'),
                    ])->columns(2),

                Forms\Components\Section::make('Phân loại và quyền truy cập')
                    ->schema([
                        Forms\Components\Select::make('material_type')
                            ->label('Loại tài liệu')
                            ->options([
                                'document' => 'Tài liệu',
                                'video' => 'Video',
                                'audio' => 'Audio',
                                'image' => 'Hình ảnh',
                                'other' => 'Khác'
                            ])
                            ->default('document')
                            ->required(),

                        Forms\Components\Select::make('access_type')
                            ->label('Quyền truy cập')
                            ->options([
                                'public' => 'Mở (ai cũng có thể xem/tải)',
                                'enrolled' => 'Dành cho học viên (chỉ hiển thị, không có nút xem/tải)'
                            ])
                            ->default('public')
                            ->required()
                            ->helperText('Tài liệu "Mở" có thể xem/tải, tài liệu "Dành cho học viên" chỉ hiển thị'),

                        Forms\Components\Toggle::make('is_preview')
                            ->label('Cho phép xem trước')
                            ->helperText('Có thể xem trước không cần đăng ký khóa học'),

                        Forms\Components\TextInput::make('order')
                            ->label('Thứ tự hiển thị')
                            ->numeric()
                            ->default(0)
                            ->helperText('Số nhỏ hơn sẽ hiển thị trước'),
                    ])->columns(2),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('Tiêu đề')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->limit(30),

                Tables\Columns\TextColumn::make('material_type')
                    ->label('Loại')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'document' => 'info',
                        'video' => 'success',
                        'audio' => 'warning',
                        'image' => 'danger',
                        'other' => 'gray',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'document' => 'Tài liệu',
                        'video' => 'Video',
                        'audio' => 'Audio',
                        'image' => 'Hình ảnh',
                        'other' => 'Khác',
                        default => $state,
                    }),

                Tables\Columns\TextColumn::make('access_type')
                    ->label('Quyền truy cập')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'public' => 'success',
                        'enrolled' => 'primary',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'public' => 'Mở',
                        'enrolled' => 'Dành cho học viên',
                        default => $state,
                    }),

                Tables\Columns\TextColumn::make('file_type')
                    ->label('Định dạng')
                    ->badge()
                    ->color('gray')
                    ->formatStateUsing(fn (string $state): string => strtoupper($state)),

                Tables\Columns\TextColumn::make('formatted_file_size')
                    ->label('Kích thước')
                    ->alignCenter(),

                Tables\Columns\IconColumn::make('is_preview')
                    ->label('Xem trước')
                    ->boolean()
                    ->trueIcon('heroicon-o-eye')
                    ->falseIcon('heroicon-o-eye-slash')
                    ->trueColor('success')
                    ->falseColor('gray'),

                Tables\Columns\TextColumn::make('order')
                    ->label('Thứ tự')
                    ->sortable()
                    ->alignCenter(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('material_type')
                    ->label('Loại tài liệu')
                    ->options([
                        'document' => 'Tài liệu',
                        'video' => 'Video',
                        'audio' => 'Audio',
                        'image' => 'Hình ảnh',
                        'other' => 'Khác'
                    ]),

                Tables\Filters\SelectFilter::make('access_type')
                    ->label('Quyền truy cập')
                    ->options([
                        'public' => 'Mở',
                        'enrolled' => 'Dành cho học viên'
                    ]),

                Tables\Filters\TernaryFilter::make('is_preview')
                    ->label('Cho phép xem trước'),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Thêm tài liệu'),
            ])
            ->actions([
                Tables\Actions\Action::make('download')
                    ->label('Tải về')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('success')
                    ->url(fn ($record) => Storage::url($record->file_path))
                    ->openUrlInNewTab()
                    ->visible(fn ($record) => $record->file_path && Storage::exists($record->file_path)),

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

    /**
     * Xác định loại tài liệu từ phần mở rộng file
     */
    private function getFileTypeFromExtension(string $extension): string
    {
        $documentTypes = ['pdf', 'doc', 'docx', 'ppt', 'pptx', 'xls', 'xlsx', 'txt'];
        $videoTypes = ['mp4', 'avi', 'mov', 'wmv', 'flv', 'webm'];
        $audioTypes = ['mp3', 'wav', 'aac', 'flac', 'ogg'];
        $imageTypes = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'svg', 'webp'];

        if (in_array($extension, $documentTypes)) {
            return 'document';
        } elseif (in_array($extension, $videoTypes)) {
            return 'video';
        } elseif (in_array($extension, $audioTypes)) {
            return 'audio';
        } elseif (in_array($extension, $imageTypes)) {
            return 'image';
        }

        return 'other';
    }
}
