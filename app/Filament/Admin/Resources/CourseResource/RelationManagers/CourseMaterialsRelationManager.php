<?php

namespace App\Filament\Admin\Resources\CourseResource\RelationManagers;

use App\Models\CourseMaterial;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Support\Enums\FontWeight;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Storage;

class CourseMaterialsRelationManager extends RelationManager
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
                            ->maxLength(255),

                        Forms\Components\Textarea::make('description')
                            ->label('Mô tả')
                            ->rows(3)
                            ->columnSpanFull(),
                    ])->columns(1),

                Forms\Components\Section::make('File tài liệu')
                    ->schema([
                        Forms\Components\FileUpload::make('file_path')
                            ->label('File tài liệu')
                            ->directory('course-materials')
                            ->acceptedFileTypes([
                                'application/pdf',
                                'application/msword',
                                'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                                'application/vnd.ms-excel',
                                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                                'application/vnd.ms-powerpoint',
                                'application/vnd.openxmlformats-officedocument.presentationml.presentation',
                                'text/plain',
                                'image/jpeg',
                                'image/png',
                                'image/webp',
                                'video/mp4',
                                'audio/mpeg',
                                'audio/wav'
                            ])
                            ->maxSize(50000) // 50MB
                            ->downloadable()
                            ->required()
                            ->helperText('Hỗ trợ: PDF, Word, Excel, PowerPoint, hình ảnh, video, audio. Tối đa 50MB'),

                        Forms\Components\Select::make('material_type')
                            ->label('Loại tài liệu')
                            ->options([
                                'document' => 'Tài liệu',
                                'video' => 'Video',
                                'audio' => 'Audio',
                                'presentation' => 'Bài thuyết trình',
                                'exercise' => 'Bài tập',
                                'image' => 'Hình ảnh',
                                'other' => 'Khác',
                            ])
                            ->required()
                            ->default('document'),

                        Forms\Components\Select::make('access_type')
                            ->label('Quyền truy cập')
                            ->options([
                                'public' => 'Công khai',
                                'enrolled' => 'Dành cho học viên',
                            ])
                            ->default('enrolled')
                            ->required()
                            ->helperText('Công khai: Ai cũng có thể xem. Dành cho học viên: Chỉ học viên đăng ký mới xem được'),
                    ])->columns(2),

                Forms\Components\Section::make('Cài đặt hiển thị')
                    ->schema([
                        Forms\Components\TextInput::make('order')
                            ->label('Thứ tự hiển thị')
                            ->numeric()
                            ->default(0)
                            ->required()
                            ->helperText('Số thứ tự để sắp xếp tài liệu (0 = đầu tiên)'),
                    ])->columns(1),
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
                    ->weight(FontWeight::Medium)
                    ->limit(40),

                Tables\Columns\TextColumn::make('material_type')
                    ->label('Loại')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'document' => 'primary',
                        'video' => 'success',
                        'audio' => 'warning',
                        'presentation' => 'info',
                        'exercise' => 'danger',
                        'image' => 'gray',
                        default => 'secondary',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'document' => 'Tài liệu',
                        'video' => 'Video',
                        'audio' => 'Audio',
                        'presentation' => 'Thuyết trình',
                        'exercise' => 'Bài tập',
                        'image' => 'Hình ảnh',
                        default => 'Khác',
                    }),

                Tables\Columns\TextColumn::make('access_type')
                    ->label('Quyền truy cập')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'public' => 'success',
                        'enrolled' => 'warning',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'public' => 'Công khai',
                        'enrolled' => 'Học viên',
                        default => 'Không xác định',
                    }),



                Tables\Columns\TextColumn::make('order')
                    ->label('Thứ tự')
                    ->sortable()
                    ->alignCenter(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Ngày tạo')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('material_type')
                    ->label('Loại tài liệu')
                    ->options([
                        'document' => 'Tài liệu',
                        'video' => 'Video',
                        'audio' => 'Audio',
                        'presentation' => 'Bài thuyết trình',
                        'exercise' => 'Bài tập',
                        'image' => 'Hình ảnh',
                        'other' => 'Khác',
                    ]),

                Tables\Filters\SelectFilter::make('access_type')
                    ->label('Quyền truy cập')
                    ->options([
                        'public' => 'Công khai',
                        'enrolled' => 'Học viên',
                    ]),


            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Thêm tài liệu')
                    ->icon('heroicon-o-plus')
                    ->mutateFormDataUsing(function (array $data): array {
                        // Tự động lấy thông tin file khi upload
                        if (isset($data['file_path']) && $data['file_path']) {
                            $filePath = $data['file_path'];
                            if (Storage::exists($filePath)) {
                                $data['file_name'] = basename($filePath);
                                $data['file_type'] = Storage::mimeType($filePath);
                                $data['file_size'] = Storage::size($filePath);
                            }
                        }
                        return $data;
                    })
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title('Thêm tài liệu thành công')
                            ->body('Tài liệu đã được thêm vào khóa học.')
                    ),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->label('Xem')
                    ->icon('heroicon-o-eye'),
                    
                Tables\Actions\EditAction::make()
                    ->label('Sửa')
                    ->icon('heroicon-o-pencil')
                    ->mutateFormDataUsing(function (array $data): array {
                        // Cập nhật thông tin file nếu có thay đổi
                        if (isset($data['file_path']) && $data['file_path']) {
                            $filePath = $data['file_path'];
                            if (Storage::exists($filePath)) {
                                $data['file_name'] = basename($filePath);
                                $data['file_type'] = Storage::mimeType($filePath);
                                $data['file_size'] = Storage::size($filePath);
                            }
                        }
                        return $data;
                    })
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title('Cập nhật tài liệu thành công')
                            ->body('Thông tin tài liệu đã được cập nhật.')
                    ),
                    
                Tables\Actions\DeleteAction::make()
                    ->label('Xóa')
                    ->icon('heroicon-o-trash')
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title('Xóa tài liệu thành công')
                            ->body('Tài liệu đã được xóa khỏi khóa học.')
                    ),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->label('Xóa đã chọn')
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title('Xóa tài liệu thành công')
                                ->body('Các tài liệu đã chọn đã được xóa.')
                        ),
                ]),
            ])
            ->defaultSort('order', 'asc')
            ->reorderable('order')
            ->emptyStateHeading('Chưa có tài liệu nào')
            ->emptyStateDescription('Thêm tài liệu đầu tiên cho khóa học này.')
            ->emptyStateIcon('heroicon-o-document-text');
    }
}
