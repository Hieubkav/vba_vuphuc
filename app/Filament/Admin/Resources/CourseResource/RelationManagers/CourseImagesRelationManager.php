<?php

namespace App\Filament\Admin\Resources\CourseResource\RelationManagers;

use App\Models\CourseImage;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Support\Enums\FontWeight;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Storage;

class CourseImagesRelationManager extends RelationManager
{
    protected static string $relationship = 'images';

    protected static ?string $title = 'Hình ảnh khóa học';

    protected static ?string $modelLabel = 'hình ảnh';

    protected static ?string $pluralModelLabel = 'hình ảnh';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Thông tin hình ảnh')
                    ->schema([
                        Forms\Components\FileUpload::make('image_link')
                            ->label('Hình ảnh')
                            ->image()
                            ->directory('courses/gallery')
                            ->visibility('public')
                            ->imageEditor()
                            ->imageEditorAspectRatios([
                                '16:9',
                                '4:3',
                                '1:1',
                                '3:2',
                            ])
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                            ->maxSize(5120)
                            ->saveUploadedFileUsing(function ($file) {
                                $courseTitle = $this->ownerRecord->title ?? 'course';
                                $customName = 'gallery-' . $courseTitle;

                                return \App\Actions\ConvertImageToWebp::run(
                                    $file,
                                    'courses/gallery',
                                    $customName,
                                    1200,
                                    800
                                );
                            })
                            ->helperText('Hình ảnh sẽ được tự động chuyển sang WebP và tối ưu kích thước. Khuyến nghị: 1200x800px')
                            ->required()
                            ->columnSpanFull(),
                    ])->columns(1),

                Forms\Components\Section::make('Cài đặt hiển thị')
                    ->schema([
                        Forms\Components\TextInput::make('order')
                            ->label('Thứ tự hiển thị')
                            ->numeric()
                            ->default(0)
                            ->required()
                            ->helperText('Số thứ tự để sắp xếp hình ảnh (0 = đầu tiên)'),

                        Forms\Components\Select::make('status')
                            ->label('Trạng thái')
                            ->options([
                                'active' => 'Hiển thị',
                                'inactive' => 'Ẩn',
                            ])
                            ->default('active')
                            ->required(),
                    ])->columns(2),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('alt_text')
            ->columns([
                Tables\Columns\ImageColumn::make('image_link')
                    ->label('Hình ảnh')
                    ->size(80)
                    ->square()
                    ->getStateUsing(function (CourseImage $record): string {
                        return $record->full_image_url;
                    }),

                Tables\Columns\TextColumn::make('alt_text')
                    ->label('Mô tả')
                    ->limit(40)
                    ->placeholder('Tự động tạo từ tên khóa học')
                    ->weight(FontWeight::Medium)
                    ->getStateUsing(function (CourseImage $record): string {
                        return $record->alt_text ?: $record->course->title;
                    }),

                Tables\Columns\TextColumn::make('status')
                    ->label('Trạng thái')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'active' => 'success',
                        'inactive' => 'gray',
                        default => 'secondary',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'active' => 'Hiển thị',
                        'inactive' => 'Ẩn',
                        default => 'Không xác định',
                    }),

                Tables\Columns\TextColumn::make('order')
                    ->label('Thứ tự')
                    ->sortable()
                    ->alignCenter(),

                Tables\Columns\TextColumn::make('file_size')
                    ->label('Kích thước')
                    ->getStateUsing(function (CourseImage $record): string {
                        if (!$record->image_link || str_starts_with($record->image_link, 'http')) {
                            return 'N/A';
                        }
                        
                        if (!Storage::disk('public')->exists($record->image_link)) {
                            return 'File không tồn tại';
                        }
                        
                        $bytes = Storage::disk('public')->size($record->image_link);
                        $units = ['B', 'KB', 'MB', 'GB'];
                        
                        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
                            $bytes /= 1024;
                        }
                        
                        return round($bytes, 2) . ' ' . $units[$i];
                    })
                    ->sortable(false),

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
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Thêm hình ảnh')
                    ->icon('heroicon-o-plus')
                    ->mutateFormDataUsing(function (array $data): array {
                        // Tự động tạo alt_text từ title của course
                        $data['alt_text'] = $this->ownerRecord->title;

                        // Đặt is_main = false (không phải hình chính)
                        $data['is_main'] = false;

                        return $data;
                    })
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title('Thêm hình ảnh thành công')
                            ->body('Hình ảnh đã được thêm vào khóa học.')
                    ),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->label('Xem')
                    ->icon('heroicon-o-eye')
                    ->modalContent(function (CourseImage $record) {
                        return view('filament.admin.course-image-preview', [
                            'image' => $record
                        ]);
                    }),
                    
                Tables\Actions\EditAction::make()
                    ->label('Sửa')
                    ->icon('heroicon-o-pencil')
                    ->mutateFormDataUsing(function (array $data): array {
                        // Tự động cập nhật alt_text từ title của course
                        $data['alt_text'] = $this->ownerRecord->title;

                        // Giữ nguyên is_main = false
                        $data['is_main'] = false;

                        return $data;
                    })
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title('Cập nhật hình ảnh thành công')
                            ->body('Thông tin hình ảnh đã được cập nhật.')
                    ),
                    
                Tables\Actions\DeleteAction::make()
                    ->label('Xóa')
                    ->icon('heroicon-o-trash')
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title('Xóa hình ảnh thành công')
                            ->body('Hình ảnh đã được xóa khỏi khóa học.')
                    ),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->label('Xóa đã chọn')
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title('Xóa hình ảnh thành công')
                                ->body('Các hình ảnh đã chọn đã được xóa.')
                        ),
                ]),
            ])
            ->defaultSort('order', 'asc')
            ->reorderable('order')
            ->emptyStateHeading('Chưa có hình ảnh nào')
            ->emptyStateDescription('Thêm hình ảnh đầu tiên cho khóa học này.')
            ->emptyStateIcon('heroicon-o-photo');
    }
}
